<?php
/**
 * seed.php — Seed the MySQL database with all static data from config.php + catalog.php.
 *
 * Run via CLI:      php seed.php
 * Run via browser:  http://localhost:8000/seed.php?run=1
 *
 * IMPORTANT: Delete or protect this file after seeding in production!
 * This script is IDEMPOTENT — uses INSERT IGNORE so it won't duplicate data.
 */

$is_cli = PHP_SAPI === 'cli';
$nl     = $is_cli ? PHP_EOL : '<br>';

if (!$is_cli && ($_GET['run'] ?? '') !== '1') {
    echo 'Add <code>?run=1</code> to the URL to run the seeder.';
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/catalog.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/data.php';

function log_ok(string $msg): void  { global $nl; echo '✓ ' . $msg . $nl; }
function log_err(string $msg): void { global $nl; echo '✗ ' . $msg . $nl; }

function seed_slugify(string $text): string {
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

try {
    $pdo = db();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    log_ok('Connected to MySQL');
    ensure_layanan_schema();
} catch (PDOException $e) {
    log_err('Connection failed: ' . $e->getMessage());
    exit(1);
}

// ─── 1. Layanan Categories + Items ────────────────────────────────────────────
echo $nl . '── Layanan ──────────────────────────────────────────' . $nl;

$sort_cat = 0;
foreach ($treatment_categories as $cid => $cat) {
    // Insert category (INSERT IGNORE skips if slug already exists)
    $pdo->prepare("INSERT IGNORE INTO layanan_categories (slug, label, sort_order) VALUES (?, ?, ?)")
        ->execute([$cid, $cat['label'], $sort_cat]);

    // Get the category id (works whether just inserted or already existed)
    $cat_id = (int) $pdo->query("SELECT id FROM layanan_categories WHERE slug = " . $pdo->quote($cid))->fetchColumn();

    log_ok("Category: [{$cid}] {$cat['label']} (id={$cat_id})");

    $sort_item = 0;
    foreach ($cat['items'] as $item) {
        $slug = seed_slugify($item['name']);
        $meta = $treatment_meta[$item['name']] ?? [null, null, null];
        $type = $meta[0] ?? null;
        $dur  = $meta[1] ?? null;
        $desc = $meta[2] ?? null;
        $img  = isset($meta[3]) ? $meta[3] : ($type ? ($treatment_image[$type] ?? null) : null);

        $gal = $treatment_media[$slug] ?? [];
        $pdo->prepare("INSERT IGNORE INTO layanan_items
            (category_id, slug, name, price, price_prefix, duration, description, img, img2, img3, sort_order, is_active)
            VALUES (?, ?, ?, ?, '', ?, ?, ?, ?, ?, ?, 1)")
            ->execute([$cat_id, $slug, $item['name'], $item['price'], $dur, $desc, $gal[0] ?? $img, $gal[1] ?? null, $gal[2] ?? null, $sort_item]);

        log_ok("  Item: {$item['name']} — Rp " . number_format($item['price'], 0, ',', '.'));
        $sort_item++;
    }
    $sort_cat++;
}

// ─── Summary ──────────────────────────────────────────────────────────────────
echo $nl;
$counts = [];
foreach (['layanan_categories','layanan_items'] as $tbl) {
    $counts[$tbl] = (int) $pdo->query("SELECT COUNT(*) FROM {$tbl}")->fetchColumn();
}
echo '── DB Summary ───────────────────────────────────────' . $nl;
foreach ($counts as $tbl => $n) {
    log_ok("{$tbl}: {$n} rows");
}
echo $nl . '✅ Seeding complete!' . $nl;
