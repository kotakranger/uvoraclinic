<?php
/**
 * admin/auth.php — Session-based auth untuk panel admin.
 *
 * Akun disimpan di tabel `admin_users` (hash bcrypt). Username & password diganti
 * lewat menu "Akun" di panel admin — tidak perlu mengedit kode.
 * Akun pertama dibuat oleh setup/migrate.php (password acak, tampil sekali).
 */

require_once __DIR__ . '/../includes/db.php';

// Jika database tidak bisa dihubungi, tampilkan penjelasan (bukan HTTP 500 kosong).
try {
    db();
} catch (Throwable $e) {
    error_log('[uvora] admin DB error: ' . $e->getMessage());
    http_response_code(503);
    $code = $e instanceof PDOException ? (string) ($e->errorInfo[1] ?? $e->getCode()) : 'config';
    $hint = [
        '1045' => 'Username atau password database salah.',
        '1049' => 'Nama database tidak ditemukan.',
        '2002' => 'Server database tidak bisa dihubungi (host/port/socket salah — di hosting kosongkan \'socket\' dan pakai host \'localhost\').',
        '2006' => 'Koneksi database terputus.',
        'config' => 'File includes/db_config.php tidak ada atau formatnya salah.',
    ][$code] ?? 'Periksa kredensial database.';
    echo '<!doctype html><meta charset="utf-8"><title>Database tidak terhubung</title>'
       . '<body style="font-family:system-ui;max-width:560px;margin:15vh auto;padding:0 1rem;color:#1a1714">'
       . '<h1 style="font-size:1.2rem">Database tidak terhubung</h1>'
       . '<p>' . htmlspecialchars($hint) . '</p>'
       . '<p style="color:#6b6459;font-size:.85rem">Perbaiki <code>includes/db_config.php</code> di hosting, lalu muat ulang. Kode: ' . htmlspecialchars($code) . '</p></body>';
    exit;
}

const ADMIN_MAX_FAILS     = 5;      // percobaan gagal ...
const ADMIN_LOCK_WINDOW   = 900;    // ... dalam 15 menit → login dikunci sementara
const ADMIN_IDLE_TIMEOUT  = 7200;   // logout otomatis setelah 2 jam tidak aktif
const ADMIN_MIN_PASS_LEN  = 12;

if (session_status() === PHP_SESSION_NONE) {
    // Use system temp dir to avoid MAMP session path issues
    if (!is_writable(ini_get('session.save_path'))) {
        session_save_path(sys_get_temp_dir());
    }
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'cookie_secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
}

/** Buat tabel akun admin & log percobaan login bila belum ada (idempotent). */
function ensure_admin_schema(): void
{
    static $done = false;
    if ($done) return;
    $done = true;
    db()->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
        username      VARCHAR(40)  NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        must_change   TINYINT(1)   NOT NULL DEFAULT 0,
        created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    db()->exec("CREATE TABLE IF NOT EXISTS admin_login_attempts (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        ip         VARCHAR(45)  NOT NULL,
        created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_ip_time (ip, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

/**
 * Buat akun pertama jika belum ada admin. Mengembalikan [username, password] bila dibuat, null bila tidak.
 * $hash diisi hanya untuk migrasi dari hash lama; selain itu password acak dibuat.
 */
function admin_bootstrap_user(?string $existing_hash = null): ?array
{
    ensure_admin_schema();
    if ((int) db()->query("SELECT COUNT(*) FROM admin_users")->fetchColumn() > 0) return null;
    $user = 'admin';
    $plain = null;
    if ($existing_hash === null) {
        $plain = substr(strtr(base64_encode(random_bytes(18)), '+/=', 'xyz'), 0, 16);
        $existing_hash = password_hash($plain, PASSWORD_BCRYPT);
    }
    db()->prepare("INSERT INTO admin_users (username, password_hash, must_change) VALUES (?,?,1)")
        ->execute([$user, $existing_hash]);
    return [$user, $plain];
}

function admin_client_ip(): string
{
    return substr($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 0, 45);
}

function admin_login_locked(): bool
{
    $st = db()->prepare("SELECT COUNT(*) FROM admin_login_attempts WHERE ip = ? AND created_at > DATE_SUB(NOW(), INTERVAL " . ADMIN_LOCK_WINDOW . " SECOND)");
    $st->execute([admin_client_ip()]);
    return (int) $st->fetchColumn() >= ADMIN_MAX_FAILS;
}

function admin_logged_in(): bool
{
    if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) return false;
    if (time() - (int) ($_SESSION['admin_last_seen'] ?? 0) > ADMIN_IDLE_TIMEOUT) {
        $_SESSION = [];
        return false;
    }
    $_SESSION['admin_last_seen'] = time();
    return true;
}

function admin_require_login(): void
{
    if (!admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
    // Akun baru / password awal: wajib ganti dulu sebelum memakai panel
    if (!empty($_SESSION['admin_must_change']) && basename($_SERVER['SCRIPT_NAME']) !== 'akun.php') {
        header('Location: akun.php?force=1');
        exit;
    }
}

/** @return string|true  true bila berhasil, selain itu pesan error untuk ditampilkan. */
function admin_login(string $user, string $pass)
{
    try {
        ensure_admin_schema();
        if (admin_login_locked()) {
            return 'Terlalu banyak percobaan gagal. Coba lagi dalam 15 menit.';
        }
        $st = db()->prepare("SELECT * FROM admin_users WHERE username = ?");
        $st->execute([$user]);
        $row = $st->fetch();
        // Verifikasi selalu dijalankan agar waktu respons tidak membocorkan username
        $hash = $row['password_hash'] ?? '$2y$10$5HzEgtaI/sijOeXEzclHyeF.fBbP2TDDKd6X4ctZPkMmFlYpmco7G';
        if (!password_verify($pass, $hash) || !$row) {
            db()->prepare("INSERT INTO admin_login_attempts (ip) VALUES (?)")->execute([admin_client_ip()]);
            return 'Username atau password salah.';
        }
    } catch (Throwable $e) {
        return 'Tidak dapat terhubung ke database.';
    }
    db()->prepare("DELETE FROM admin_login_attempts WHERE ip = ?")->execute([admin_client_ip()]);
    if (password_needs_rehash($row['password_hash'], PASSWORD_BCRYPT)) {
        db()->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?")
            ->execute([password_hash($pass, PASSWORD_BCRYPT), $row['id']]);
    }
    session_regenerate_id(true);
    $_SESSION['admin_logged_in']   = true;
    $_SESSION['admin_uid']         = (int) $row['id'];
    $_SESSION['admin_user']        = $row['username'];
    $_SESSION['admin_must_change'] = (bool) $row['must_change'];
    $_SESSION['admin_last_seen']   = time();
    return true;
}

function admin_logout(): void
{
    $_SESSION = [];
    session_destroy();
}
