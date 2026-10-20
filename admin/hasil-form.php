<?php
/**
 * admin/hasil-form.php — Halaman tambah / edit hasil before-after.
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/uploader.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/data.php';

admin_require_login();
ensure_results_schema();

$pdo   = db();
$token = csrf_token();
$id    = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$row   = [];

if ($id) {
    $st = $pdo->prepare("SELECT * FROM results WHERE id=?");
    $st->execute([$id]);
    $row = $st->fetch() ?: [];
    if (!$row) { flash_set('err', 'Data tidak ditemukan.'); header('Location: hasil.php'); exit; }
}

$form_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $label     = trim($_POST['label'] ?? '');
    $treatment = trim($_POST['treatment'] ?? '');
    $desc      = trim($_POST['description'] ?? '');
    $active    = isset($_POST['is_active']) ? 1 : 0;
    try {
        $before = handle_image_field('before_img', 'hasil', $_POST['before_img'] ?? '');
        $after  = handle_image_field('after_img',  'hasil', $_POST['after_img']  ?? '');
    } catch (RuntimeException $e) {
        $form_error = $e->getMessage();
    }
    if (!$form_error && $label === '')                    $form_error = 'Kondisi kulit wajib diisi.';
    if (!$form_error && ($before === '' || $after === '')) $form_error = 'Foto before dan after wajib diisi.';

    if (!$form_error) {
        try {
            if ($id) {
                $pdo->prepare("UPDATE results SET label=?, treatment=?, description=?, before_img=?, after_img=?, is_active=? WHERE id=?")
                    ->execute([$label, $treatment, $desc, $before, $after, $active, $id]);
                flash_set('ok', 'Hasil diperbarui.');
            } else {
                $sort = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),-1)+1 FROM results")->fetchColumn();
                $pdo->prepare("INSERT INTO results (label, treatment, description, before_img, after_img, sort_order, is_active) VALUES (?,?,?,?,?,?,?)")
                    ->execute([$label, $treatment, $desc, $before, $after, $sort, $active]);
                flash_set('ok', 'Hasil ditambahkan.');
            }
            header('Location: hasil.php'); exit;
        } catch (PDOException $e) {
            $form_error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
    $row = array_merge($row, $_POST, ['id' => $id, 'is_active' => $active]);
}

$v = $row;
$active_page = 'hasil';
admin_head($id ? 'Edit Hasil' : 'Tambah Hasil', $active_page);
?>
<div style="max-width:760px">
  <p style="margin-bottom:1rem"><a href="hasil.php" style="color:var(--muted);font-size:.85rem;">← Kembali ke daftar hasil</a></p>
  <?php if ($form_error): ?><div class="flash flash-err" style="margin-bottom:1rem"><?= he($form_error) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="card card-body">
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="form-group">
      <label class="lbl">Kondisi Kulit *</label>
      <input type="text" name="label" required value="<?= he($v['label'] ?? '') ?>" placeholder="mis. Jerawat & Bekas">
      <p class="hint">Jadi nama tab di section Hasil.</p>
    </div>
    <div class="form-group">
      <label class="lbl">Perawatan yang Dijalani</label>
      <input type="text" name="treatment" value="<?= he($v['treatment'] ?? '') ?>" placeholder="mis. Acne Hydrafacial">
    </div>
    <div class="form-group">
      <label class="lbl">Keterangan</label>
      <textarea name="description" rows="3" placeholder="mis. Direkomendasikan 4–6 sesi dengan chemical peeling bertahap."><?= he($v['description'] ?? '') ?></textarea>
    </div>
    <?php image_field('before_img', 'Foto Before (Sebelum) *', '', $v['before_img'] ?? ''); ?>
    <?php image_field('after_img',  'Foto After (Sesudah) *',  '', $v['after_img']  ?? ''); ?>
    <p class="hint" style="margin:-6px 0 14px">Gunakan foto dengan sudut &amp; ukuran yang sama agar slider sebelum/sesudah rapi. Pastikan ada persetujuan pasien.</p>

    <div class="form-group">
      <label class="toggle-wrap">
        <span class="toggle"><input type="checkbox" name="is_active" <?= !$id || !empty($v['is_active']) ? 'checked' : '' ?>><span class="slider"></span></span>
        Aktif (tampil di website)
      </label>
    </div>
    <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:.5rem">
      <a href="hasil.php" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
</div>
<?php admin_foot(); ?>
