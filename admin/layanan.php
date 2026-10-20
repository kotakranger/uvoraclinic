<?php
/**
 * admin/layanan.php — CMS untuk Layanan (categories + items)
 *
 * Actions:
 *  POST add_cat    — tambah kategori
 *  POST edit_cat   — edit kategori
 *  GET  del_cat    — hapus kategori
 *  (tambah/edit layanan item → layanan-form.php, halaman terpisah)
 *  GET  del_item   — hapus item
 *  GET  toggle     — toggle is_active item
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/uploader.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/data.php';

admin_require_login();

ensure_layanan_schema();


$pdo    = db();

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$token  = csrf_token();

// ── Handle POST actions ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {

    if ($action === 'add_cat') {
        $label = trim($_POST['label'] ?? '');
        $slug  = make_slug($_POST['slug'] ?? $label);
        $sort  = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),-1)+1 FROM layanan_categories")->fetchColumn();
        if ($label) {
            try {
                $pdo->prepare("INSERT INTO layanan_categories (slug, label, sort_order) VALUES (?,?,?)")
                    ->execute([$slug, $label, $sort]);
                flash_set('ok', "Kategori \"{$label}\" berhasil ditambahkan.");
            } catch (PDOException $e) {
                flash_set('err', 'Slug sudah ada atau error: ' . $e->getMessage());
            }
        }
        header('Location: layanan.php'); exit;
    }

    if ($action === 'edit_cat') {
        $id    = (int)($_POST['id'] ?? 0);
        $label = trim($_POST['label'] ?? '');
        $slug  = make_slug($_POST['slug'] ?? $label);
        if ($id && $label) {
            try {
                $pdo->prepare("UPDATE layanan_categories SET slug=?, label=? WHERE id=?")
                    ->execute([$slug, $label, $id]);
                flash_set('ok', "Kategori diperbarui.");
            } catch (PDOException $e) {
                flash_set('err', 'Error: ' . $e->getMessage());
            }
        }
        header('Location: layanan.php'); exit;
    }
}

// ── GET actions ────────────────────────────────────────────────────────────
if ($action === 'del_cat' && isset($_GET['id']) && csrf_verify()) {
    $pdo->prepare("DELETE FROM layanan_categories WHERE id=?")->execute([(int)$_GET['id']]);
    flash_set('ok', 'Kategori dihapus.');
    header('Location: layanan.php'); exit;
}
if ($action === 'del_item' && isset($_GET['id']) && csrf_verify()) {
    $pdo->prepare("DELETE FROM layanan_items WHERE id=?")->execute([(int)$_GET['id']]);
    flash_set('ok', 'Layanan dihapus.');
    header('Location: layanan.php'); exit;
}
if ($action === 'toggle' && isset($_GET['id'])) {
    $pdo->prepare("UPDATE layanan_items SET is_active = 1 - is_active WHERE id=?")->execute([(int)$_GET['id']]);
    header('Location: layanan.php'); exit;
}

// ── Fetch data ──────────────────────────────────────────────────────────────
$categories = $pdo->query(
    "SELECT * FROM layanan_categories ORDER BY sort_order, label"
)->fetchAll();

$items = $pdo->query(
    "SELECT li.*, lc.label AS cat_label
     FROM layanan_items li
     LEFT JOIN layanan_categories lc ON lc.id = li.category_id
     ORDER BY lc.sort_order, lc.label, li.sort_order, li.name"
)->fetchAll();

$active_page = 'layanan';
admin_head('Layanan', $active_page);
?>

<?php flash_html(); ?>

<!-- ── Stat strip ── -->
<div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
  <div class="card" style="padding:.75rem 1.25rem;flex:1;min-width:120px;">
    <p style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Kategori</p>
    <p style="font-size:1.8rem;font-weight:700;"><?= count($categories) ?></p>
  </div>
  <div class="card" style="padding:.75rem 1.25rem;flex:1;min-width:120px;">
    <p style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Total Layanan</p>
    <p style="font-size:1.8rem;font-weight:700;"><?= count($items) ?></p>
  </div>
  <div class="card" style="padding:.75rem 1.25rem;flex:1;min-width:120px;">
    <p style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Aktif</p>
    <p style="font-size:1.8rem;font-weight:700;color:var(--success);"><?= count(array_filter($items, fn($i) => $i['is_active'])) ?></p>
  </div>
</div>

<!-- ── CATEGORIES ── -->
<div class="card" style="margin-bottom:1.5rem;">
  <div class="card-header">
    <h2>Kategori Layanan</h2>
    <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-cat')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Tambah Kategori
    </button>
  </div>
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr><th>Label</th><th>Slug</th><th>Items</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($categories)): ?>
        <tr><td colspan="5" class="empty-state">Belum ada kategori.</td></tr>
        <?php else: ?>
        <?php foreach ($categories as $cat):
          $item_count = count(array_filter($items, fn($i) => $i['category_id'] == $cat['id']));
        ?>
        <tr>
          <td><strong><?= he($cat['label']) ?></strong></td>
          <td><code style="font-size:.78rem;color:var(--muted);"><?= he($cat['slug']) ?></code></td>
          <td><?= $item_count ?></td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <button class="btn btn-secondary btn-sm"
                onclick="editCat(<?= htmlspecialchars(json_encode($cat)) ?>)">Edit</button>
              <button type="button" class="btn btn-danger btn-sm" onclick='confirmDelete("?action=del_cat&id=<?= $cat["id"] ?>&csrf_token=<?= $token ?>", "kategori")'>Hapus</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ── ITEMS ── -->
<div class="card">
  <div class="card-header">
    <h2>Daftar Layanan</h2>
    <a class="btn btn-primary btn-sm" href="layanan-form.php">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Tambah Layanan
    </a>
  </div>
  <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);display:flex;flex-direction:column;gap:.75rem;">
    <div class="search-wrap" style="max-width:420px;">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input class="search-input" type="search" id="svc-search" placeholder="Cari nama layanan…" autocomplete="off">
    </div>
    <div class="filter-tabs" id="svc-cats">
      <button type="button" class="filter-tab active" data-cat="all">Semua <span class="count"><?= count($items) ?></span></button>
      <?php foreach ($categories as $cat): ?>
      <button type="button" class="filter-tab" data-cat="<?= (int)$cat['id'] ?>"><?= he($cat['label']) ?> <span class="count"><?= count(array_filter($items, fn($i) => $i['category_id'] == $cat['id'])) ?></span></button>
      <?php endforeach; ?>
    </div>
    <p class="hint" id="svc-count" style="margin:0;"></p>
  </div>
  <div class="tbl-wrap">
    <table id="svc-table">
      <thead>
        <tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($items)): ?>
        <tr><td colspan="7" class="empty-state">Belum ada layanan. Tambahkan di atas.</td></tr>
        <?php else: ?>
        <?php foreach ($items as $it): ?>
        <tr data-svc data-cat="<?= (int)$it['category_id'] ?>" data-name="<?= he(mb_strtolower($it['name'] . ' ' . $it['slug'])) ?>">
          <td>
            <?php if ($it['img']): ?>
              <img src="../<?= he($it['img']) ?>" class="img-preview" alt="">
            <?php else: ?>
              <div style="width:48px;height:48px;border-radius:6px;background:#f3f0ec;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#c4bdb5" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m3 9 5-5 4 4 4-4 5 5M3 21l5-5 4 4 4-4 5 5"/></svg>
              </div>
            <?php endif; ?>
          </td>
          <td>
            <strong><?= he($it['name']) ?></strong>
            <div style="font-size:.74rem;color:var(--muted);"><?= he($it['slug']) ?></div>
          </td>
          <td><?= he($it['cat_label'] ?? '-') ?></td>
          <td>
            <?php if ($it['price']): ?>
              Rp <?= number_format($it['price'], 0, ',', '.') ?>
            <?php else: ?>
              <span style="color:var(--muted);">—</span>
            <?php endif; ?>
          </td>
          <td><?= he($it['duration'] ?: '—') ?></td>
          <td>
            <a href="layanan.php?action=toggle&id=<?= $it['id'] ?>" title="Toggle aktif/nonaktif">
              <span class="tag <?= $it['is_active'] ? 'tag-active' : 'tag-inactive' ?>">
                <?= $it['is_active'] ? 'Aktif' : 'Nonaktif' ?>
              </span>
            </a>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <a class="btn btn-secondary btn-sm" href="layanan-form.php?id=<?= (int)$it['id'] ?>">Edit</a>
              <button type="button" class="btn btn-danger btn-sm" onclick='confirmDelete("?action=del_item&id=<?= $it["id"] ?>&csrf_token=<?= $token ?>", "layanan")'>Hapus</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr id="svc-none" style="display:none;"><td colspan="7" class="empty-state">Tidak ada layanan yang cocok. Coba kata kunci atau kategori lain.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ═══════════════════ MODALS ═══════════════════ -->

<!-- Add Category -->
<div class="modal-overlay" id="modal-add-cat">
  <div class="modal">
    <div class="modal-header">
      <h3>Tambah Kategori</h3>
      <button class="modal-close" onclick="closeModal('modal-add-cat')">×</button>
    </div>
    <form method="POST" action="layanan.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="add_cat">
      <div class="form-group">
        <label class="lbl">Label Kategori *</label>
        <input type="text" name="label" required placeholder="mis. Hydrafacial" oninput="autoSlug(this,'slug-cat-add')">
      </div>
      <div class="form-group">
        <label class="lbl">Slug (URL)</label>
        <input type="text" name="slug" id="slug-cat-add" placeholder="mis. hydrafacial">
        <p class="hint">Otomatis dari label. Hanya huruf kecil, angka, tanda-hubung.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-cat')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Category -->
<div class="modal-overlay" id="modal-edit-cat">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit Kategori</h3>
      <button class="modal-close" onclick="closeModal('modal-edit-cat')">×</button>
    </div>
    <form method="POST" action="layanan.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="edit_cat">
      <input type="hidden" name="id" id="ec-id">
      <div class="form-group">
        <label class="lbl">Label Kategori *</label>
        <input type="text" name="label" id="ec-label" required>
      </div>
      <div class="form-group">
        <label class="lbl">Slug</label>
        <input type="text" name="slug" id="ec-slug">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-cat')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id) {
  document.getElementById(id).classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(function(o) {
  o.addEventListener('click', function(e) {
    if (e.target === o) o.classList.remove('open');
  });
});
// Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(function(o){ o.classList.remove('open'); });
});

function autoSlug(input, targetId) {
  var slug = input.value.toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9\-]/g, '');
  document.getElementById(targetId).value = slug;
}

// ── Cari + filter kategori (daftar layanan) ──
(function () {
  var rows = Array.prototype.slice.call(document.querySelectorAll('tr[data-svc]'));
  if (!rows.length) return;
  var input = document.getElementById('svc-search');
  var tabs  = document.querySelectorAll('#svc-cats .filter-tab');
  var none  = document.getElementById('svc-none');
  var info  = document.getElementById('svc-count');
  var cat = 'all';
  try { cat = sessionStorage.getItem('svcCat') || 'all'; } catch (e) {}

  function apply() {
    var q = input.value.trim().toLowerCase(), shown = 0;
    rows.forEach(function (r) {
      var ok = (cat === 'all' || r.dataset.cat === cat) && (!q || r.dataset.name.indexOf(q) !== -1);
      r.style.display = ok ? '' : 'none';
      if (ok) shown++;
    });
    none.style.display = shown ? 'none' : '';
    info.textContent = 'Menampilkan ' + shown + ' dari ' + rows.length + ' layanan';
    tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.cat === cat); });
  }
  tabs.forEach(function (t) {
    t.addEventListener('click', function () {
      cat = t.dataset.cat;
      try { sessionStorage.setItem('svcCat', cat); } catch (e) {}
      apply();
    });
  });
  input.addEventListener('input', apply);
  if (![].some.call(tabs, function (t) { return t.dataset.cat === cat; })) cat = 'all';
  apply();
})();

function editCat(cat) {
  document.getElementById('ec-id').value    = cat.id;
  document.getElementById('ec-label').value = cat.label;
  document.getElementById('ec-slug').value  = cat.slug;
  openModal('modal-edit-cat');
}

</script>

<?php admin_foot(); ?>
