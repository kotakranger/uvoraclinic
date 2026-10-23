<?php
/**
 * media.php — menyajikan foto upload dari storage di luar docroot.
 * .htaccess mengarahkan assets/uploads/<sub>/<file> ke sini hanya bila file tidak ada di folder website.
 */
require __DIR__ . '/includes/storage.php';

$d = (string) ($_GET['d'] ?? '');
$f = (string) ($_GET['f'] ?? '');
$types = ['webp' => 'image/webp', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
$ext   = strtolower(pathinfo($f, PATHINFO_EXTENSION));

$path = (isset($types[$ext]) && preg_match('/^[a-z0-9_-]+$/i', $d))
    ? uv_upload_file("assets/uploads/$d/$f")
    : null;

if ($path === null) {
    http_response_code(404);
    header('Cache-Control: no-store');   // jangan biarkan CDN menyimpan 404 (foto bisa di-upload ulang)
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Not found';
    exit;
}

$mtime = filemtime($path);
$etag  = '"' . dechex($mtime) . '-' . dechex(filesize($path)) . '"';
header('ETag: ' . $etag);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
header('Cache-Control: public, max-age=31536000, immutable');   // nama file unik per upload
header('X-Content-Type-Options: nosniff');
if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $etag) {
    http_response_code(304);
    exit;
}
header('Content-Type: ' . $types[$ext]);
header('Content-Length: ' . filesize($path));
readfile($path);
