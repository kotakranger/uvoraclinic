<?php
/**
 * admin/layanan-form.php — Halaman tambah / edit layanan (item).
 *   GET  layanan-form.php          → form tambah
 *   GET  layanan-form.php?id=N     → form edit
 *   POST (sama)                    → simpan
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/uploader.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/data.php';

admin_require_login();
ensure_layanan_schema();

$pdo   = db();
$token = csrf_token();
$id    = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$row   = [];

if ($id) {
    $st = $pdo->prepare("SELECT * FROM layanan_items WHERE id=?");
    $st->execute([$id]);
    $row = $st->fetch() ?: [];
    if (!$row) { flash_set('err', 'Layanan tidak ditemukan.'); header('Location: layanan.php'); exit; }
}
$categories = $pdo->query("SELECT * FROM layanan_categories ORDER BY sort_order, label")->fetchAll();

function lines_clean(string $v): string
{
    return implode("\n", array_filter(array_map('trim', preg_split('/\R/', $v)), 'strlen'));
}

$form_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $cat_id = (int)($_POST['category_id'] ?? 0);
    $name   = trim($_POST['name'] ?? '');
    $slug   = make_slug(($_POST['slug'] ?? '') !== '' ? $_POST['slug'] : $name);
    $price  = (int)preg_replace('/\D/', '', $_POST['price'] ?? '0');
    $dur    = trim($_POST['duration'] ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $active = isset($_POST['is_active']) ? 1 : 0;

    try {
        $img  = handle_image_field('img',  'layanan', $_POST['img']  ?? '');
        $img2 = handle_image_field('img2', 'layanan', $_POST['img2'] ?? '');
        $img3 = handle_image_field('img3', 'layanan', $_POST['img3'] ?? '');
    } catch (RuntimeException $e) {
        $form_error = $e->getMessage();
    }

    if (!$form_error && (!$cat_id || $name === '')) {
        $form_error = 'Kategori dan nama layanan wajib diisi.';
    }

    if (!$form_error) {
        $detail = [
            trim($_POST['how_title'] ?? ''), trim($_POST['how_text'] ?? ''),
            lines_clean($_POST['features'] ?? ''), lines_clean($_POST['suitable'] ?? ''),
            lines_clean($_POST['notes'] ?? ''),    lines_clean($_POST['steps'] ?? ''),
        ];
        try {
            if ($id) {
                $pdo->prepare("UPDATE layanan_items SET category_id=?, slug=?, name=?, price=?, price_prefix='', duration=?, description=?,
                        img=?, img2=?, img3=?, how_title=?, how_text=?, features=?, suitable=?, notes=?, steps=?, is_active=? WHERE id=?")
                    ->execute(array_merge([$cat_id, $slug, $name, $price, $dur, $desc, $img, $img2, $img3], $detail, [$active, $id]));
                flash_set('ok', 'Layanan diperbarui.');
            } else {
                $sort = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),-1)+1 FROM layanan_items")->fetchColumn();
                $pdo->prepare("INSERT INTO layanan_items (category_id, slug, name, price, price_prefix, duration, description,
                        img, img2, img3, how_title, how_text, features, suitable, notes, steps, sort_order, is_active)
                        VALUES (?,?,?,?,'',?,?,?,?,?,?,?,?,?,?,?,?,?)")
                    ->execute(array_merge([$cat_id, $slug, $name, $price, $dur, $desc, $img, $img2, $img3], $detail, [$sort, $active]));
                flash_set('ok', "Layanan \"{$name}\" ditambahkan.");
            }
            header('Location: layanan.php'); exit;
        } catch (PDOException $e) {
            $form_error = 'Gagal menyimpan (slug mungkin sudah dipakai): ' . $e->getMessage();
        }
    }
    // keep what the admin typed when validation fails
    $row = array_merge($row, $_POST, ['id' => $id, 'is_active' => $active]);
}

$v = $row;
$active_page = 'layanan';
admin_head($id ? 'Edit Layanan' : 'Tambah Layanan', $active_page);
?>
<div style="max-width:760px">
  <p style="margin-bottom:1rem"><a href="layanan.php" style="color:var(--muted);font-size:.85rem;">← Kembali ke daftar layanan</a></p>
  <?php if ($form_error): ?><div class="flash flash-err" style="margin-bottom:1rem"><?= he($form_error) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="card card-body">
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="form-group">
      <label class="lbl">Kategori *</label>
      <select name="category_id" required>
        <option value="">— pilih kategori —</option>
        <?php foreach ($categories as $c): ?>
        <option value="<?= $c['id'] ?>" <?= ($v['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= he($c['label']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label class="lbl">Nama Layanan *</label>
      <input type="text" name="name" required value="<?= he($v['name'] ?? '') ?>" placeholder="mis. Acne Hydrafacial" <?= $id ? '' : "oninput=\"document.getElementById('f-slug').value=this.value.toLowerCase().replace(/\\s+/g,'-').replace(/[^a-z0-9\\-]/g,'')\"" ?>>
    </div>
    <div class="form-group">
      <label class="lbl">Slug (URL)</label>
      <input type="text" name="slug" id="f-slug" value="<?= he($v['slug'] ?? '') ?>" placeholder="otomatis dari nama">
      <p class="hint">Dipakai di link halaman detail. Mengubah slug pada layanan lama akan mengubah link-nya.</p>
    </div>
    <div class="form-grid form-grid-2">
      <div class="form-group">
        <label class="lbl">Harga (Rp)</label>
        <input type="text" inputmode="numeric" name="price" value="<?= he((string)($v['price'] ?? '')) ?>" placeholder="259000">
      </div>
      <div class="form-group">
        <label class="lbl">Durasi</label>
        <input type="text" name="duration" value="<?= he($v['duration'] ?? '') ?>" placeholder="45 menit">
      </div>
    </div>
    <div class="form-group">
      <label class="lbl">Deskripsi</label>
      <textarea name="description" rows="3" placeholder="Deskripsi singkat layanan…"><?= he($v['description'] ?? '') ?></textarea>
    </div>

    <?php image_field('img', 'Foto Utama (sampul)', '', $v['img'] ?? ''); ?>
    <?php include __DIR__ . '/partials/_layanan_detail_form.php'; ?>

    <div class="form-group">
      <label class="toggle-wrap">
        <span class="toggle"><input type="checkbox" name="is_active" <?= !$id || !empty($v['is_active']) ? 'checked' : '' ?>><span class="slider"></span></span>
        Aktif (tampil di website)
      </label>
    </div>
    <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:.5rem">
      <a href="layanan.php" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
</div>
<?php admin_foot(); ?>
