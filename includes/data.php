<?php
/**
 * includes/data.php — Data layer untuk frontend.
 *
 * Membaca data dinamis (dokter, testimoni, promo, layanan, reels)
 * dari database. Jika tabel kosong, fallback ke data di config.php.
 *
 * Harus di-require SETELAH config.php dan db.php.
 */

// ── Helpers ──────────────────────────────────────────────────────────────────

function _db_rows(string $sql, array $params = []): array
{
    try {
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

// ── Doctors ───────────────────────────────────────────────────────────────────

function load_doctors(array $fallback): array
{
    $rows = _db_rows(
        "SELECT * FROM doctors WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
    );
    if (!$rows) return $fallback;

    return array_map(function ($r) {
        $exp = [];
        if (!empty($r['expertise_json'])) {
            $decoded = json_decode($r['expertise_json'], true);
            if (is_array($decoded)) $exp = $decoded;
        }
        return [
            'name'      => $r['name'],
            'role'      => $r['role'],
            'img'       => $r['img'] ?? '',
            'schedule'  => $r['schedule'] ?? '',
            'expertise' => $exp,
        ];
    }, $rows);
}

// ── Testimonials ──────────────────────────────────────────────────────────────

function load_testimonials(array $fallback): array
{
    $rows = _db_rows(
        "SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
    );
    if (!$rows) return $fallback;

    return array_map(fn($r) => [
        'quote' => $r['quote'],
        'name'  => $r['author'],   // DB column is 'author'
        'city'  => $r['city'] ?? '',
    ], $rows);
}

// ── Promotions ────────────────────────────────────────────────────────────────

function load_promotions(array $fallback): array
{
    $rows = _db_rows(
        "SELECT * FROM promos WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
    );
    if (!$rows) return $fallback;

    return array_map(function ($r) {
        $terms = [];
        if (!empty($r['terms_json'])) {
            $decoded = json_decode($r['terms_json'], true);
            if (is_array($decoded)) $terms = $decoded;
        }
        return [
            'slug'     => $r['slug'],
            'title'    => $r['title'],
            'subtitle' => $r['subtitle'] ?? '',
            'banner'   => $r['banner'] ?? '',
            'period'   => $r['period'] ?? '',
            'category' => $r['category'] ?? '',
            'desc'     => $r['description'] ?? '',
            'terms'    => $terms,
        ];
    }, $rows);
}

/**
 * Cari satu promo by slug — cek DB dulu, lalu fallback ke array $promotions.
 */
function find_promo_by_slug(string $slug, array $fallback_list): ?array
{
    $rows = _db_rows(
        "SELECT * FROM promos WHERE slug = ? AND is_active = 1 LIMIT 1",
        [$slug]
    );
    if ($rows) {
        $r     = $rows[0];
        $terms = [];
        if (!empty($r['terms_json'])) {
            $decoded = json_decode($r['terms_json'], true);
            if (is_array($decoded)) $terms = $decoded;
        }
        return [
            'slug'     => $r['slug'],
            'title'    => $r['title'],
            'subtitle' => $r['subtitle'] ?? '',
            'banner'   => $r['banner'] ?? '',
            'period'   => $r['period'] ?? '',
            'category' => $r['category'] ?? '',
            'desc'     => $r['description'] ?? '',
            'terms'    => $terms,
        ];
    }
    // Fallback: cari di config.php $promotions
    foreach ($fallback_list as $p) {
        if ($p['slug'] === $slug) return $p;
    }
    return null;
}

// ── Layanan (categories + items) ─────────────────────────────────────────────

/** Add detail/gallery columns to layanan_items when missing (idempotent). */
function ensure_layanan_schema(): void
{
    static $done = false;
    if ($done) return;
    $done = true;
    try {
        $have = array_column(db()->query("SHOW COLUMNS FROM layanan_items")->fetchAll(), 'Field');
        $add = [
            'img2'      => 'VARCHAR(255) NULL',
            'img3'      => 'VARCHAR(255) NULL',
            'how_title' => 'VARCHAR(200) NULL',
            'how_text'  => 'TEXT NULL',
            'features'  => 'TEXT NULL',
            'suitable'  => 'TEXT NULL',
            'notes'     => 'TEXT NULL',
            'steps'     => 'TEXT NULL',
        ];
        foreach ($add as $col => $def) {
            if (!in_array($col, $have, true)) {
                db()->exec("ALTER TABLE layanan_items ADD COLUMN $col $def");
            }
        }
    } catch (Throwable $e) { /* DB unavailable: front falls back to config.php */ }
}

function load_treatment_categories(array $fallback): array
{
    ensure_layanan_schema();
    $cats = _db_rows(
        "SELECT * FROM layanan_categories ORDER BY sort_order ASC, id ASC"
    );
    if (!$cats) return $fallback;

    $items_all = _db_rows(
        "SELECT * FROM layanan_items WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
    );

    $result = [];
    foreach ($cats as $cat) {
        $items = array_values(array_filter($items_all, fn($i) => $i['category_id'] == $cat['id']));
        if (!$items) continue; // skip kategori kosong
        $result[$cat['slug']] = [
            'label' => $cat['label'],
            'items' => array_map(fn($i) => [
                'name'   => $i['name'],
                'price'  => (int) $i['price'],
                'desc'   => $i['description'] ?? '',
                'dur'    => $i['duration'] ?? '',
                'slug'   => $i['slug'] ?? '',
                'img'    => $i['img'] ?? '',
                'img2'   => $i['img2'] ?? '',
                'img3'   => $i['img3'] ?? '',
                'how_title' => $i['how_title'] ?? '',
                'how_text'  => $i['how_text'] ?? '',
                'features'  => $i['features'] ?? '',
                'suitable'  => $i['suitable'] ?? '',
                'notes'     => $i['notes'] ?? '',
                'steps'     => $i['steps'] ?? '',
            ], $items),
        ];
    }
    return $result ?: $fallback;
}

/** Apakah section dinamis punya konten aktif? Dipakai menu navigasi agar tidak menampilkan link ke section kosong. */
function site_has(string $what): bool
{
    static $cache = [];
    if (!isset($cache[$what])) {
        $sql = [
            'doctors'      => "SELECT COUNT(*) AS c FROM doctors WHERE is_active = 1",
            'testimonials' => "SELECT COUNT(*) AS c FROM testimonials WHERE is_active = 1",
            'promos'       => "SELECT COUNT(*) AS c FROM promos WHERE is_active = 1",
            'results'      => "SELECT COUNT(*) AS c FROM results WHERE is_active = 1",
        ][$what] ?? null;
        $rows = $sql ? _db_rows($sql) : [];
        $cache[$what] = !empty($rows[0]['c']);
    }
    return $cache[$what];
}

// ── Hasil (before / after) ───────────────────────────────────────────────────

function ensure_results_schema(): void
{
    static $done = false;
    if ($done) return;
    $done = true;
    try {
        db()->exec("CREATE TABLE IF NOT EXISTS results (
            id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
            label       VARCHAR(100)  NOT NULL,
            treatment   VARCHAR(150)  NOT NULL DEFAULT '',
            description TEXT,
            before_img  VARCHAR(255)  NOT NULL DEFAULT '',
            after_img   VARCHAR(255)  NOT NULL DEFAULT '',
            sort_order  SMALLINT      NOT NULL DEFAULT 0,
            is_active   TINYINT(1)    NOT NULL DEFAULT 1,
            created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    } catch (Throwable $e) { /* DB unavailable */ }
}

/** Hasil before/after dari admin. Kosong => section "Hasil" disembunyikan. */
function load_results(): array
{
    ensure_results_schema();
    $rows = _db_rows(
        "SELECT * FROM results WHERE is_active = 1 AND before_img <> '' AND after_img <> '' ORDER BY sort_order ASC, id ASC"
    );
    return array_map(fn($r) => [
        'id'        => 'r' . $r['id'],
        'label'     => $r['label'],
        'treatment' => $r['treatment'],
        'text'      => $r['description'] ?? '',
        'before'    => $r['before_img'],
        'after'     => $r['after_img'],
    ], $rows);
}

// ── Content Reels ─────────────────────────────────────────────────────────────

function load_content_reels(array $fallback): array
{
    $rows = _db_rows(
        "SELECT * FROM content_reels WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
    );
    if (!$rows) return $fallback;

    return array_map(fn($r) => [
        'embed_url' => $r['embed_url'],
        'caption'   => $r['caption'] ?? '',
    ], $rows);
}
