<?php
/**
 * migrate.php — One-time schema runner for Uvora Clinic MySQL database.
 *
 * Run via browser: http://localhost:8000/migrate.php
 * Or via CLI:      php migrate.php
 *
 * IMPORTANT: Delete or protect this file after running in production!
 * Add to .htaccess:  <Files "migrate.php"> Deny from all </Files>
 *
 * This script is IDEMPOTENT — safe to run multiple times.
 * It uses CREATE TABLE IF NOT EXISTS so it won't destroy existing data.
 */

// --- CLI or browser detection ---
$is_cli = PHP_SAPI === 'cli';
$nl     = $is_cli ? PHP_EOL : '<br>';

if (!$is_cli) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
    <title>Migrate · Uvora Clinic</title>
    <style>body{font-family:monospace;max-width:700px;margin:2rem auto;padding:1rem}
    .ok{color:#16a34a}.err{color:#dc2626}.warn{color:#d97706}
    h1{font-size:1.1rem;margin-bottom:1rem}pre{background:#f4f4f4;padding:1rem;border-radius:8px}
    </style></head><body><h1>🗄 Uvora Clinic — DB Migration</h1><pre>';
}

function log_msg(string $msg, string $type = 'ok'): void {
    global $is_cli, $nl;
    if ($is_cli) {
        echo $msg . $nl;
    } else {
        $cls = htmlspecialchars($type);
        echo '<span class="' . $cls . '">' . htmlspecialchars($msg) . '</span>' . $nl;
    }
    ob_flush(); flush();
}

// --- Basic protection: block in production unless ?run=1 or CLI ---
if (!$is_cli && ($_GET['run'] ?? '') !== '1') {
    echo 'Add <code>?run=1</code> to the URL to execute the migration.' . $nl;
    echo '</pre></body></html>';
    exit;
}

require_once __DIR__ . '/includes/db.php';

try {
    $pdo = db();
    log_msg('✓ Connected to MySQL', 'ok');
} catch (PDOException $e) {
    log_msg('✗ Connection failed: ' . $e->getMessage(), 'err');
    if (!$is_cli) echo '</pre></body></html>';
    exit(1);
}

// ─── Schema definitions ────────────────────────────────────────────────────
$tables = [

    // ── Consultations (from the contact form) ─────────────────────────────
    'consultations' => "CREATE TABLE IF NOT EXISTS consultations (
        id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
        name        VARCHAR(100)    NOT NULL,
        phone       VARCHAR(30)     NOT NULL,
        treatment   VARCHAR(200)    NOT NULL,
        message     TEXT,
        ip          VARCHAR(45),
        user_agent  VARCHAR(255),
        is_read     TINYINT(1)      NOT NULL DEFAULT 0,
        created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_created (created_at),
        INDEX idx_is_read (is_read)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Rate limiting (per-IP, per-hour cap for form submissions) ─────────
    'rate_limits' => "CREATE TABLE IF NOT EXISTS rate_limits (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        ip         VARCHAR(45)  NOT NULL,
        created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_ip_time (ip, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Layanan categories (e.g. Hydrafacial, Peeling, …) ────────────────
    'layanan_categories' => "CREATE TABLE IF NOT EXISTS layanan_categories (
        id         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
        slug       VARCHAR(80)     NOT NULL UNIQUE,
        label      VARCHAR(100)    NOT NULL,
        sort_order SMALLINT        NOT NULL DEFAULT 0,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Layanan items (individual treatments) ─────────────────────────────
    'layanan_items' => "CREATE TABLE IF NOT EXISTS layanan_items (
        id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
        category_id INT UNSIGNED    NOT NULL,
        slug        VARCHAR(120)    NOT NULL UNIQUE,
        name        VARCHAR(150)    NOT NULL,
        price       INT UNSIGNED    NOT NULL DEFAULT 0,
        price_prefix VARCHAR(20),
        duration    VARCHAR(60),
        description TEXT,
        img         VARCHAR(255),
        sort_order  SMALLINT        NOT NULL DEFAULT 0,
        is_active   TINYINT(1)      NOT NULL DEFAULT 1,
        created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (category_id) REFERENCES layanan_categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Promotions ────────────────────────────────────────────────────────
    'promos' => "CREATE TABLE IF NOT EXISTS promos (
        id         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
        slug       VARCHAR(120)    NOT NULL UNIQUE,
        title      VARCHAR(150)    NOT NULL,
        subtitle   VARCHAR(255),
        banner     VARCHAR(255),
        period     VARCHAR(100),
        category   VARCHAR(60),
        description TEXT,
        terms_json TEXT            COMMENT 'JSON array of term strings',
        sort_order SMALLINT        NOT NULL DEFAULT 0,
        is_active  TINYINT(1)      NOT NULL DEFAULT 1,
        created_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Doctors ───────────────────────────────────────────────────────────
    'doctors' => "CREATE TABLE IF NOT EXISTS doctors (
        id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name           VARCHAR(120) NOT NULL,
        role           VARCHAR(120),
        img            VARCHAR(255),
        schedule       VARCHAR(150),
        expertise_json TEXT         COMMENT 'JSON array of expertise strings',
        sort_order     SMALLINT     NOT NULL DEFAULT 0,
        is_active      TINYINT(1)   NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Testimonials ──────────────────────────────────────────────────────
    'testimonials' => "CREATE TABLE IF NOT EXISTS testimonials (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        quote      TEXT         NOT NULL,
        author     VARCHAR(80)  NOT NULL,
        city       VARCHAR(80),
        sort_order SMALLINT     NOT NULL DEFAULT 0,
        is_active  TINYINT(1)   NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Content Reels (TikTok / IG / YouTube embed URLs) ──────────────────
    'content_reels' => "CREATE TABLE IF NOT EXISTS content_reels (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        embed_url  VARCHAR(500) NOT NULL,
        sort_order SMALLINT     NOT NULL DEFAULT 0,
        is_active  TINYINT(1)   NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // ── Media library ─────────────────────────────────────────────────────
    'media' => "CREATE TABLE IF NOT EXISTS media (
        id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
        filename      VARCHAR(255) NOT NULL UNIQUE,
        original_name VARCHAR(255) NOT NULL,
        mime_type     VARCHAR(80)  NOT NULL,
        size_bytes    INT UNSIGNED NOT NULL DEFAULT 0,
        created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

];

// ─── Run each table creation ───────────────────────────────────────────────
$all_ok = true;
foreach ($tables as $name => $sql) {
    try {
        $pdo->exec($sql);
        log_msg("✓ Table `{$name}` ready", 'ok');
    } catch (PDOException $e) {
        log_msg("✗ Table `{$name}` failed: " . $e->getMessage(), 'err');
        $all_ok = false;
    }
}

// ─── Clean up old rate_limits rows (older than 24h) ───────────────────────
try {
    $deleted = $pdo->exec("DELETE FROM rate_limits WHERE created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    log_msg("✓ Cleaned {$deleted} old rate_limit rows", 'ok');
} catch (PDOException $e) {
    log_msg("⚠ Could not clean rate_limits: " . $e->getMessage(), 'warn');
}

log_msg('', 'ok');
if ($all_ok) {
    log_msg('✅ Migration complete — all tables are ready.', 'ok');
    log_msg('⚠  Remember to delete or protect migrate.php before going live!', 'warn');
} else {
    log_msg('❌ Migration finished with errors — check above.', 'err');
}

if (!$is_cli) echo '</pre></body></html>';
