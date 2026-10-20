<?php
/**
 * admin/auth.php — Lightweight session-based auth for the admin panel.
 *
 * IMPORTANT: Change ADMIN_PASSWORD before going live!
 * Generate a hash with: php -r "echo password_hash('your-password', PASSWORD_BCRYPT);"
 */

define('ADMIN_USER',     'admin');
// Default password: uvora2026  — CHANGE THIS before production!
define('ADMIN_PASSWORD_HASH', '$2y$12$eP7ecaDIx46.0jctJLeKtuherC6u9s3503pxIhkgPXuBGH5PsNCx2');

if (session_status() === PHP_SESSION_NONE) {
    // Use system temp dir to avoid MAMP session path issues
    if (!is_writable(ini_get('session.save_path'))) {
        session_save_path(sys_get_temp_dir());
    }
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
    ]);
}

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function admin_require_login(): void
{
    if (!admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function admin_login(string $user, string $pass): bool
{
    if ($user !== ADMIN_USER) return false;
    if (!password_verify($pass, ADMIN_PASSWORD_HASH)) return false;
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    return true;
}

function admin_logout(): void
{
    $_SESSION = [];
    session_destroy();
}
