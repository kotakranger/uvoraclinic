<?php
/**
 * api/consult.php — Consultation form submission handler
 *
 * Security layers:
 *  1. CSRF token validation
 *  2. Honeypot field (bots fill it, humans don't)
 *  3. Cloudflare Turnstile server-side verification
 *  4. Server-side input validation & sanitisation
 *  5. Rate limiting (5 submissions per IP per hour)
 *  6. Saves to MySQL and redirects user to WhatsApp
 */

require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/data.php';

header('Content-Type: application/json; charset=utf-8');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// ── 1. CSRF check ──────────────────────────────────────────────────────────
if (!csrf_verify()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

// ── 2. Honeypot check ─────────────────────────────────────────────────────
// Bots fill every field — the hidden `website` field must stay empty.
if (!empty($_POST['website'])) {
    // Silently return OK so the bot thinks it succeeded
    echo json_encode(['ok' => true, 'whatsapp' => '']);
    exit;
}

// ── 3. Cloudflare Turnstile verification (hanya bila kunci dikonfigurasi) ──
if (turnstile_enabled()) {
    $ts_token = trim($_POST['cf-turnstile-response'] ?? '');
    if ($ts_token === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Verifikasi keamanan gagal. Muat ulang halaman dan coba lagi.']);
        exit;
    }
    $ts_verify = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false,
        stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query([
                'secret'   => turnstile_config()['secret'],
                'response' => $ts_token,
                'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
            ]),
            'timeout' => 5,
        ]])
    );
    $ts_result = $ts_verify ? json_decode($ts_verify, true) : null;
    if (empty($ts_result['success'])) {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'Verifikasi keamanan gagal. Silakan coba lagi.']);
        exit;
    }
}

// ── 4. Input validation & sanitisation ───────────────────────────────────
$name      = trim(strip_tags($_POST['name']      ?? ''));
$phone     = trim(strip_tags($_POST['phone']     ?? ''));
$treatment = trim(strip_tags($_POST['treatment'] ?? ''));
$message   = trim(strip_tags($_POST['message']   ?? ''));
$visit_date = trim($_POST['visit_date'] ?? '');
$visit_time = trim($_POST['visit_time'] ?? '');

$errors = [];
if (mb_strlen($name) < 2 || mb_strlen($name) > 100)        $errors[] = 'Nama tidak valid.';
if (!preg_match('/^[\d\s\+\-\(\)]{7,20}$/', $phone))       $errors[] = 'Nomor WhatsApp tidak valid.';
if (empty($treatment))                                       $errors[] = 'Pilih perawatan.';
if (mb_strlen($message) > 1000)                             $errors[] = 'Pesan terlalu panjang.';

$d = DateTime::createFromFormat('!Y-m-d', $visit_date);
$today = new DateTime('today');
if (!$d || $d->format('Y-m-d') !== $visit_date)             $errors[] = 'Tanggal kunjungan tidak valid.';
elseif ($d < $today || $d > (clone $today)->modify('+90 days')) $errors[] = 'Pilih tanggal kunjungan dalam 90 hari ke depan.';
if (!preg_match('/^(1[0-7]):([03]0)$/', $visit_time))        $errors[] = 'Jam kunjungan tidak valid (10.00–17.30 WIB).';

if ($errors) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => implode(' ', $errors)]);
    exit;
}

// ── 4. Rate limiting — max 5 per IP per hour ──────────────────────────────
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$ip = filter_var(trim(explode(',', $ip)[0]), FILTER_VALIDATE_IP) ?: '0.0.0.0';

try {
    $pdo = db();
    ensure_consultations_schema();

    // Count recent submissions from this IP in the last hour
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM rate_limits
         WHERE ip = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)"
    );
    $stmt->execute([$ip]);
    if ((int) $stmt->fetchColumn() >= 5) {
        http_response_code(429);
        echo json_encode(['ok' => false, 'error' => 'Terlalu banyak permintaan. Coba lagi dalam 1 jam.']);
        exit;
    }

    // Record this attempt
    $pdo->prepare("INSERT INTO rate_limits (ip) VALUES (?)")->execute([$ip]);

    // ── 5. Save consultation ──────────────────────────────────────────────
    $pdo->prepare(
        "INSERT INTO consultations (name, phone, treatment, message, visit_date, visit_time, ip, user_agent)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    )->execute([
        $name,
        $phone,
        $treatment,
        $message ?: null,
        $visit_date,
        $visit_time,
        $ip,
        mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);

} catch (PDOException $e) {
    error_log('[uvora] DB error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Terjadi kesalahan server. Silakan coba lagi.']);
    exit;
}

// ── 6. Build WhatsApp link — same format as before ────────────────────────
$jadwal = $d->format('d-m-Y') . ' pukul ' . str_replace(':', '.', $visit_time) . ' WIB';
$text = "Halo Uvora, saya {$name} ingin booking perawatan {$treatment} pada {$jadwal}.";
if ($message) $text .= " Saya memiliki keluhan: {$message}";
$wa = 'https://wa.me/' . $site['whatsapp'] . '?text=' . rawurlencode($text);

echo json_encode(['ok' => true, 'whatsapp' => $wa]);
