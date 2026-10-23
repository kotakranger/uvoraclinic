<?php
/**
 * db.php — MySQL PDO singleton for Uvora Clinic.
 *
 * Credentials live in includes/db_config.php (gitignored).
 * Copy includes/db_config.example.php → db_config.php to get started.
 *
 * The db() function returns a shared PDO instance; calling it multiple
 * times in the same request is free — it reuses the existing connection.
 */

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    require_once __DIR__ . '/storage.php';
    $cfg = require uv_config_file('db_config.php');

    // Build DSN: prefer Unix socket (MAMP) over TCP/IP (Hostinger / production)
    if (!empty($cfg['socket'])) {
        $dsn = sprintf(
            'mysql:unix_socket=%s;dbname=%s;charset=utf8mb4',
            $cfg['socket'],
            $cfg['dbname']
        );
    } else {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $cfg['host'],
            (int) ($cfg['port'] ?? 3306),
            $cfg['dbname']
        );
    }

    $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_FOUND_ROWS   => true,
    ]);

    return $pdo;
}
