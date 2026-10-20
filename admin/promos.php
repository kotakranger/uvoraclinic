<?php
/**
 * admin/promos.php — CMS untuk Promo
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/uploader.php';
require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$pdo    = db();
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$token  = csrf_token();

if ($action === 'del_promo' && isset($_GET['id']) && csrf_verify()) {
    $pdo->prepare("DELETE FROM promos WHERE id=?")->execute([(int)$_GET['id']]);
    flash_set('ok', 'Promo dihapus.');
    header('Location: promos.php'); exit;
}
if ($action === 'toggle' && isset($_GET['id']) && csrf_verify()) {
    $pdo->prepare("UPDATE promos SET is_active = 1 - is_active WHERE id=?")->execute([(int)$_GET['id']]);
    header('Location: promos.php'); exit;
}

$promos = $pdo->query("SELECT * FROM promos ORDER BY sort_order, created_at DESC")->fetchAll();

$active_page = 'promos';
admin_head('Promo', $active_page);
?>

<?php flash_html(); ?>

<div class="toolbar">
  <span style="font-size:.85rem;color:var(--muted);"><?= count($promos) ?> promo</span>
  <a class="btn btn-primary btn-sm" style="margin-left:auto;" href="promo-form.php">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    Tambah Promo
  </a>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr><th>Banner</th><th>Judul</th><th>Kategori</th><th>Periode</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($promos)): ?>
        <tr><td colspan="6" class="empty-state">Belum ada promo. Tambahkan di atas.</td></tr>
        <?php else: ?>
        <?php foreach ($promos as $p): ?>
        <tr>
          <td>
            <?php if ($p['banner']): ?>
              <img src="../<?= he($p['banner']) ?>" class="img-preview" alt="">
            <?php else: ?>
              <div style="width:48px;height:48px;border-radius:6px;background:#f3f0ec;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#c4bdb5" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m3 9 5-5 4 4 4-4 5 5"/></svg>
              </div>
            <?php endif; ?>
          </td>
          <td>
            <strong><?= he($p['title']) ?></strong>
            <?php if ($p['subtitle']): ?>
            <div style="font-size:.74rem;color:var(--muted);"><?= he($p['subtitle']) ?></div>
            <?php endif; ?>
          </td>
          <td><?= he($p['category'] ?: '—') ?></td>
          <td style="font-size:.78rem;"><?= he($p['period'] ?: '—') ?></td>
          <td>
            <a href="promos.php?action=toggle&id=<?= $p['id'] ?>&csrf_token=<?= $token ?>">
              <span class="tag <?= $p['is_active'] ? 'tag-active' : 'tag-inactive' ?>">
                <?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?>
              </span>
            </a>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <a class="btn btn-secondary btn-sm" href="promo-form.php?id=<?= (int)$p['id'] ?>">Edit</a>
              <button type="button" class="btn btn-danger btn-sm" onclick='confirmDelete("?action=del_promo&id=<?= $p["id"] ?>&csrf_token=<?= $token ?>", "promo")'>Hapus</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php admin_foot(); ?>
