<?php
/**
 * storage.php — lokasi file runtime yang TIDAK boleh ikut tertimpa deploy.
 *
 * Deploy GitHub → hosting menimpa isi folder website (public_html). Foto upload dari admin
 * dan kredensial database tidak ada di git, jadi kalau disimpan di dalam folder itu mereka
 * bisa hilang setiap push. Semuanya disimpan di folder "uvora-storage" yang sejajar dengan
 * public_html (satu level di atasnya), di luar jangkauan deploy:
 *
 *   .../domains/uvoraclinic.com/public_html/    ← kode (di-deploy)
 *   .../domains/uvoraclinic.com/uvora-storage/  ← data runtime (aman)
 *       uploads/{hasil,doctors,layanan,promos}/
 *       db_config.php                           (opsional)
 *
 * Override lokasi: define('UV_STORAGE_DIR', '/path') atau env UV_STORAGE_DIR.
 * Jika folder di luar tidak bisa dibuat/ditulis (mis. open_basedir), jatuh balik ke
 * lokasi lama di dalam folder website supaya situs tetap jalan.
 */

/** Folder storage di luar docroot, atau null bila tidak tersedia. */
function uv_storage_root(): ?string
{
    static $root = false;
    if ($root !== false) return $root;

    $cand = defined('UV_STORAGE_DIR') ? UV_STORAGE_DIR : (getenv('UV_STORAGE_DIR') ?: '');
    if ($cand === '') $cand = dirname(__DIR__, 2) . '/uvora-storage';
    $cand = rtrim($cand, '/');

    if (!is_dir($cand)) @mkdir($cand, 0755, true);
    $root = (is_dir($cand) && is_writable($cand)) ? $cand : null;
    return $root;
}

/** Folder tujuan upload untuk satu subfolder (hasil|doctors|layanan|promos). Dibuat bila belum ada. */
function uv_upload_dir(string $subdir): string
{
    $subdir = preg_replace('/[^a-z0-9_-]/i', '', $subdir);
    $root   = uv_storage_root();
    $dir    = $root !== null
        ? $root . '/uploads/' . $subdir
        : dirname(__DIR__) . '/assets/uploads/' . $subdir;   // fallback lama
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        throw new RuntimeException('Folder upload tidak bisa dibuat.');
    }
    return $dir;
}

/**
 * Path file asli untuk nilai yang tersimpan di database ("assets/uploads/<sub>/<file>"),
 * atau null bila file tidak ada. Mengecek storage di luar docroot dulu, lalu lokasi lama.
 */
function uv_upload_file(string $rel): ?string
{
    if (!preg_match('#^/?assets/uploads/([a-z0-9_-]+)/([A-Za-z0-9._-]+)$#i', $rel, $m)) return null;
    $root = uv_storage_root();
    $paths = [];
    if ($root !== null) $paths[] = $root . '/uploads/' . $m[1] . '/' . $m[2];
    $paths[] = dirname(__DIR__) . '/assets/uploads/' . $m[1] . '/' . $m[2];
    foreach ($paths as $p) if (is_file($p)) return $p;
    return null;
}

/** Apakah gambar ada? URL http(s) dianggap ada; path lokal dicek di storage dan folder lama. */
function uv_image_exists(string $p): bool
{
    if (preg_match('#^https?://#i', $p)) return true;
    if (uv_upload_file($p) !== null) return true;
    return is_file(dirname(__DIR__) . '/' . ltrim($p, '/'));   // aset statis, mis. assets/images/…
}

/** File config rahasia: storage di luar docroot diutamakan, lalu includes/. */
function uv_config_file(string $name): string
{
    $root = uv_storage_root();
    if ($root !== null && is_file($root . '/' . $name)) return $root . '/' . $name;
    return __DIR__ . '/' . $name;
}
