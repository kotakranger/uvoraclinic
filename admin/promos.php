<?php
/**
 * admin/promos.php — CMS untuk Promo
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$pdo    = db();
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$token  = csrf_token();

// ── POST handlers ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {

    $name     = trim($_POST['title'] ?? '');
    $slug     = make_slug($_POST['slug'] ?? $name);
    $subtitle = trim($_POST['subtitle'] ?? '');
    $banner   = trim($_POST['banner'] ?? '');
    $period   = trim($_POST['period'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $terms    = trim($_POST['terms'] ?? '');
    $sort     = (int)($_POST['sort_order'] ?? 0);
    $active   = isset($_POST['is_active']) ? 1 : 0;
    // terms: one per line → JSON array
    $terms_json = $terms ? json_encode(array_values(array_filter(array_map('trim', explode("\n", $terms))))) : null;

    if ($action === 'add_promo' && $name) {
        try {
            $pdo->prepare("INSERT INTO promos (slug, title, subtitle, banner, period, category, description, terms_json, sort_order, is_active)
                VALUES (?,?,?,?,?,?,?,?,?,?)")
                ->execute([$slug, $name, $subtitle, $banner, $period, $category, $desc, $terms_json, $sort, $active]);
            flash_set('ok', "Promo \"{$name}\" berhasil ditambahkan.");
        } catch (PDOException $e) {
            flash_set('err', 'Error: ' . $e->getMessage());
        }
        header('Location: promos.php'); exit;
    }

    if ($action === 'edit_promo') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id && $name) {
            try {
                $pdo->prepare("UPDATE promos SET slug=?, title=?, subtitle=?, banner=?, period=?, category=?,
                    description=?, terms_json=?, sort_order=?, is_active=? WHERE id=?")
                    ->execute([$slug, $name, $subtitle, $banner, $period, $category, $desc, $terms_json, $sort, $active, $id]);
                flash_set('ok', "Promo diperbarui.");
            } catch (PDOException $e) {
                flash_set('err', 'Error: ' . $e->getMessage());
            }
        }
        header('Location: promos.php'); exit;
    }
}

if ($action === 'del_promo' && isset($_GET['id']) && csrf_verify()) {
    $pdo->prepare("DELETE FROM promos WHERE id=?")->execute([(int)$_GET['id']]);
    flash_set('ok', 'Promo dihapus.');
    header('Location: promos.php'); exit;
}
if ($action === 'toggle' && isset($_GET['id'])) {
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
  <button class="btn btn-primary btn-sm" style="margin-left:auto;" onclick="openModal('modal-add-promo')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    Tambah Promo
  </button>
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
            <a href="promos.php?action=toggle&id=<?= $p['id'] ?>">
              <span class="tag <?= $p['is_active'] ? 'tag-active' : 'tag-inactive' ?>">
                <?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?>
              </span>
            </a>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <button class="btn btn-secondary btn-sm"
                onclick='editPromo(<?= htmlspecialchars(json_encode($p)) ?>)'>Edit</button>
              <a class="btn btn-danger btn-sm"
                href="promos.php?action=del_promo&id=<?= $p['id'] ?>&_token=<?= $token ?>"
                onclick="return confirm('Hapus promo ini?')">Hapus</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Promo Modal -->
<div class="modal-overlay" id="modal-add-promo">
  <div class="modal" style="max-width:620px;">
    <div class="modal-header">
      <h3>Tambah Promo</h3>
      <button class="modal-close" onclick="closeModal('modal-add-promo')">×</button>
    </div>
    <form method="POST" action="promos.php">
      <input type="hidden" name="_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="add_promo">
      <?php include __DIR__ . '/partials/_promo_form.php'; ?>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-promo')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Promo Modal -->
<div class="modal-overlay" id="modal-edit-promo">
  <div class="modal" style="max-width:620px;">
    <div class="modal-header">
      <h3>Edit Promo</h3>
      <button class="modal-close" onclick="closeModal('modal-edit-promo')">×</button>
    </div>
    <form method="POST" action="promos.php">
      <input type="hidden" name="_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="edit_promo">
      <input type="hidden" name="id" id="ep-id">
      <?php include __DIR__ . '/partials/_promo_form.php'; ?>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-promo')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(function(o) {
  o.addEventListener('click', function(e) { if (e.target === o) o.classList.remove('open'); });
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(function(o){ o.classList.remove('open'); });
});
function autoSlug(input, targetId) {
  document.getElementById(targetId).value = input.value.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9\-]/g,'');
}
function editPromo(p) {
  document.getElementById('ep-id').value = p.id;
  // Fill fields in edit modal by using name selectors scoped to modal
  var m = document.getElementById('modal-edit-promo');
  m.querySelector('[name=title]').value       = p.title || '';
  m.querySelector('[name=slug]').value        = p.slug || '';
  m.querySelector('[name=subtitle]').value    = p.subtitle || '';
  m.querySelector('[name=banner]').value      = p.banner || '';
  m.querySelector('[name=period]').value      = p.period || '';
  m.querySelector('[name=category]').value    = p.category || '';
  m.querySelector('[name=description]').value = p.description || '';
  var terms = [];
  try { terms = JSON.parse(p.terms_json || '[]'); } catch(e){}
  m.querySelector('[name=terms]').value = terms.join('\n');
  m.querySelector('[name=sort_order]').value  = p.sort_order || 0;
  m.querySelector('[name=is_active]').checked = p.is_active == 1;
  openModal('modal-edit-promo');
}
</script>

<?php admin_foot(); ?>
