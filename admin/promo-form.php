<?php
/**
 * admin/promo-form.php — Halaman tambah / edit promo.
 *   GET promo-form.php        → tambah
 *   GET promo-form.php?id=N   → edit
 *   POST (sama)               → simpan
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/uploader.php';
require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$pdo   = db();
$token = csrf_token();
$id    = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$row   = [];

if ($id) {
    $st = $pdo->prepare("SELECT * FROM promos WHERE id=?");
    $st->execute([$id]);
    $row = $st->fetch() ?: [];
    if (!$row) { flash_set('err', 'Promo tidak ditemukan.'); header('Location: promos.php'); exit; }
    $row['terms'] = implode("\n", json_decode($row['terms_json'] ?? '[]', true) ?: []);
}

$form_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $title    = trim($_POST['title'] ?? '');
    $slug     = make_slug(($_POST['slug'] ?? '') !== '' ? $_POST['slug'] : $title);
    $subtitle = trim($_POST['subtitle'] ?? '');
    $period   = trim($_POST['period'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $terms    = array_values(array_filter(array_map('trim', preg_split('/\R/', (string)($_POST['terms'] ?? ''))), 'strlen'));
    $terms_json = $terms ? json_encode($terms, JSON_UNESCAPED_UNICODE) : null;
    $active   = isset($_POST['is_active']) ? 1 : 0;

    try {
        $banner = handle_image_field('banner', 'promos', $_POST['banner'] ?? '');
    } catch (RuntimeException $e) {
        $form_error = $e->getMessage();
    }
    if (!$form_error && $title === '') {
        $form_error = 'Judul promo wajib diisi.';
    }

    if (!$form_error) {
        try {
            if ($id) {
                $pdo->prepare("UPDATE promos SET slug=?, title=?, subtitle=?, banner=?, period=?, category=?, description=?, terms_json=?, is_active=? WHERE id=?")
                    ->execute([$slug, $title, $subtitle, $banner, $period, $category, $desc, $terms_json, $active, $id]);
                flash_set('ok', 'Promo diperbarui.');
            } else {
                $sort = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),-1)+1 FROM promos")->fetchColumn();
                $pdo->prepare("INSERT INTO promos (slug, title, subtitle, banner, period, category, description, terms_json, sort_order, is_active)
                        VALUES (?,?,?,?,?,?,?,?,?,?)")
                    ->execute([$slug, $title, $subtitle, $banner, $period, $category, $desc, $terms_json, $sort, $active]);
                flash_set('ok', "Promo \"{$title}\" ditambahkan.");
            }
            header('Location: promos.php'); exit;
        } catch (PDOException $e) {
            $form_error = 'Gagal menyimpan (slug mungkin sudah dipakai): ' . $e->getMessage();
        }
    }
    $row = array_merge($row, $_POST, ['id' => $id, 'is_active' => $active]);
}

$v = $row;
$active_page = 'promos';
admin_head($id ? 'Edit Promo' : 'Tambah Promo', $active_page);
?>
<div style="max-width:760px">
  <p style="margin-bottom:1rem"><a href="promos.php" style="color:var(--muted);font-size:.85rem;">← Kembali ke daftar promo</a></p>
  <?php if ($form_error): ?><div class="flash flash-err" style="margin-bottom:1rem"><?= he($form_error) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="card card-body">
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="form-group">
      <label class="lbl">Judul Promo *</label>
      <input type="text" name="title" required value="<?= he($v['title'] ?? '') ?>" placeholder="mis. Promo Lebaran 20%" <?= $id ? '' : "oninput=\"document.getElementById('f-slug').value=this.value.toLowerCase().replace(/\\s+/g,'-').replace(/[^a-z0-9\\-]/g,'')\"" ?>>
    </div>
    <div class="form-group">
      <label class="lbl">Slug (URL)</label>
      <input type="text" name="slug" id="f-slug" value="<?= he($v['slug'] ?? '') ?>" placeholder="otomatis dari judul">
    </div>
    <div class="form-group">
      <label class="lbl">Subtitle / Tagline</label>
      <input type="text" name="subtitle" value="<?= he($v['subtitle'] ?? '') ?>" placeholder="Hemat hingga 20% untuk semua perawatan wajah">
    </div>
    <?php image_field('banner', 'Banner Promo', '', $v['banner'] ?? ''); ?>
    <div class="form-grid form-grid-2">
      <div class="form-group">
        <label class="lbl">Periode</label>
        <input type="text" name="period" value="<?= he($v['period'] ?? '') ?>" placeholder="mis. 1–31 Maret 2025">
      </div>
      <div class="form-group">
        <label class="lbl">Kategori</label>
        <input type="text" name="category" value="<?= he($v['category'] ?? '') ?>" placeholder="mis. Seasonal, Referral…">
      </div>
    </div>
    <div class="form-group">
      <label class="lbl">Deskripsi</label>
      <textarea name="description" rows="4" placeholder="Detail promo, apa yang didapat…"><?= he($v['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label class="lbl">Syarat &amp; Ketentuan</label>
      <textarea name="terms" rows="5" placeholder="Satu syarat per baris"><?= he($v['terms'] ?? '') ?></textarea>
      <p class="hint">Satu baris = satu item syarat &amp; ketentuan.</p>
    </div>
    <div class="form-group">
      <label class="toggle-wrap">
        <span class="toggle"><input type="checkbox" name="is_active" <?= !$id || !empty($v['is_active']) ? 'checked' : '' ?>><span class="slider"></span></span>
        Aktif (tampil di website)
      </label>
    </div>
    <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:.5rem">
      <a href="promos.php" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
</div>
<?php admin_foot(); ?>
