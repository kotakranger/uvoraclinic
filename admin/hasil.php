<?php
/**
 * admin/hasil.php — Daftar Hasil (before / after).
 * Tambah/edit → hasil-form.php
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/data.php';

admin_require_login();
ensure_results_schema();

$pdo    = db();
$action = $_GET['action'] ?? '';
$token  = csrf_token();

if ($action === 'del' && isset($_GET['id']) && csrf_verify()) {
    $pdo->prepare("DELETE FROM results WHERE id=?")->execute([(int)$_GET['id']]);
    flash_set('ok', 'Hasil dihapus.');
    header('Location: hasil.php'); exit;
}
if ($action === 'toggle' && isset($_GET['id']) && csrf_verify()) {
    $pdo->prepare("UPDATE results SET is_active = 1 - is_active WHERE id=?")->execute([(int)$_GET['id']]);
    header('Location: hasil.php'); exit;
}

$rows = $pdo->query("SELECT * FROM results ORDER BY sort_order, id")->fetchAll();

$active_page = 'hasil';
admin_head('Hasil (Before/After)', $active_page);
flash_html();
?>
<div class="toolbar">
  <span style="font-size:.85rem;color:var(--muted);"><?= count($rows) ?> hasil · tampil di section "Hasil Berbicara" pada website</span>
  <a class="btn btn-primary btn-sm" style="margin-left:auto;" href="hasil-form.php">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    Tambah Hasil
  </a>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Before</th><th>After</th><th>Kondisi / Perawatan</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if (!$rows): ?>
        <tr><td colspan="5" class="empty-state">Belum ada hasil. Section "Hasil" disembunyikan di website sampai ada data.</td></tr>
        <?php else: foreach ($rows as $r): ?>
        <tr>
          <td><?php if ($r['before_img']): ?><img src="../<?= he($r['before_img']) ?>" class="img-preview" alt=""><?php endif; ?></td>
          <td><?php if ($r['after_img']): ?><img src="../<?= he($r['after_img']) ?>" class="img-preview" alt=""><?php endif; ?></td>
          <td>
            <strong><?= he($r['label']) ?></strong>
            <div style="font-size:.74rem;color:var(--muted);"><?= he($r['treatment'] ?: '—') ?></div>
          </td>
          <td>
            <a href="hasil.php?action=toggle&id=<?= (int)$r['id'] ?>&csrf_token=<?= $token ?>">
              <span class="tag <?= $r['is_active'] ? 'tag-active' : 'tag-inactive' ?>"><?= $r['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
            </a>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <a class="btn btn-secondary btn-sm" href="hasil-form.php?id=<?= (int)$r['id'] ?>">Edit</a>
              <button type="button" class="btn btn-danger btn-sm" onclick='confirmDelete("?action=del&id=<?= (int)$r["id"] ?>&csrf_token=<?= $token ?>", "hasil")'>Hapus</button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_foot(); ?>
