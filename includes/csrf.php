<?php
/**
 * csrf.php — CSRF token helpers (session-based, double-submit pattern)
 * Usage:
 *   csrf_token()        → returns current token string
 *   csrf_field()        → outputs hidden <input> HTML
 *   csrf_verify()       → throws on invalid token (call in POST handlers)
 */

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

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verify CSRF token from POST body, GET param, or X-CSRF-Token header.
 * Returns false (does NOT throw) so callers can send JSON errors.
 */
function csrf_verify(): bool
{
    $token = $_POST['csrf_token']
        ?? ($_GET['csrf_token']
        ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));

    return hash_equals(csrf_token(), (string) $token);
}
