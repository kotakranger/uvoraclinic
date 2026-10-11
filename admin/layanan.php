<?php
/**
 * admin/layanan.php — CMS untuk Layanan (categories + items)
 *
 * Actions:
 *  POST add_cat    — tambah kategori
 *  POST edit_cat   — edit kategori
 *  GET  del_cat    — hapus kategori
 *  POST add_item   — tambah layanan item
 *  POST edit_item  — edit layanan item
 *  GET  del_item   — hapus item
 *  GET  toggle     — toggle is_active item
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$pdo    = db();
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$token  = csrf_token();

// ── Handle POST actions ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {

    if ($action === 'add_cat') {
        $label = trim($_POST['label'] ?? '');
        $slug  = make_slug($_POST['slug'] ?? $label);
        $sort  = (int)($_POST['sort_order'] ?? 0);
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
        $sort  = (int)($_POST['sort_order'] ?? 0);
        if ($id && $label) {
            try {
                $pdo->prepare("UPDATE layanan_categories SET slug=?, label=?, sort_order=? WHERE id=?")
                    ->execute([$slug, $label, $sort, $id]);
                flash_set('ok', "Kategori diperbarui.");
            } catch (PDOException $e) {
                flash_set('err', 'Error: ' . $e->getMessage());
            }
        }
        header('Location: layanan.php'); exit;
    }

    if ($action === 'add_item') {
        $cat_id  = (int)($_POST['category_id'] ?? 0);
        $name    = trim($_POST['name'] ?? '');
        $slug    = make_slug($_POST['slug'] ?? $name);
        $price   = (int)preg_replace('/\D/', '', $_POST['price'] ?? '0');
        $prefix  = trim($_POST['price_prefix'] ?? 'Mulai Rp');
        $dur     = trim($_POST['duration'] ?? '');
        $desc    = trim($_POST['description'] ?? '');
        $img     = trim($_POST['img'] ?? '');
        $sort    = (int)($_POST['sort_order'] ?? 0);
        $active  = isset($_POST['is_active']) ? 1 : 0;

        if ($cat_id && $name) {
            try {
                $pdo->prepare("INSERT INTO layanan_items
                    (category_id, slug, name, price, price_prefix, duration, description, img, sort_order, is_active)
                    VALUES (?,?,?,?,?,?,?,?,?,?)")
                    ->execute([$cat_id, $slug, $name, $price, $prefix, $dur, $desc, $img, $sort, $active]);
                flash_set('ok', "Layanan \"{$name}\" berhasil ditambahkan.");
            } catch (PDOException $e) {
                flash_set('err', 'Error: ' . $e->getMessage());
            }
        }
        header('Location: layanan.php'); exit;
    }

    if ($action === 'edit_item') {
        $id      = (int)($_POST['id'] ?? 0);
        $cat_id  = (int)($_POST['category_id'] ?? 0);
        $name    = trim($_POST['name'] ?? '');
        $slug    = make_slug($_POST['slug'] ?? $name);
        $price   = (int)preg_replace('/\D/', '', $_POST['price'] ?? '0');
        $prefix  = trim($_POST['price_prefix'] ?? 'Mulai Rp');
        $dur     = trim($_POST['duration'] ?? '');
        $desc    = trim($_POST['description'] ?? '');
        $img     = trim($_POST['img'] ?? '');
        $sort    = (int)($_POST['sort_order'] ?? 0);
        $active  = isset($_POST['is_active']) ? 1 : 0;

        if ($id && $name) {
            try {
                $pdo->prepare("UPDATE layanan_items
                    SET category_id=?, slug=?, name=?, price=?, price_prefix=?,
                        duration=?, description=?, img=?, sort_order=?, is_active=?
                    WHERE id=?")
                    ->execute([$cat_id, $slug, $name, $price, $prefix, $dur, $desc, $img, $sort, $active, $id]);
                flash_set('ok', "Layanan diperbarui.");
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
        <tr><th>Label</th><th>Slug</th><th>Urutan</th><th>Items</th><th>Aksi</th></tr>
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
          <td><?= (int)$cat['sort_order'] ?></td>
          <td><?= $item_count ?></td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <button class="btn btn-secondary btn-sm"
                onclick="editCat(<?= htmlspecialchars(json_encode($cat)) ?>)">Edit</button>
              <a class="btn btn-danger btn-sm"
                href="layanan.php?action=del_cat&id=<?= $cat['id'] ?>&_token=<?= $token ?>"
                onclick="return confirm('Hapus kategori ini? Semua item di dalamnya ikut terhapus!')">Hapus</a>
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
    <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-item')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Tambah Layanan
    </button>
  </div>
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($items)): ?>
        <tr><td colspan="7" class="empty-state">Belum ada layanan. Tambahkan di atas.</td></tr>
        <?php else: ?>
        <?php foreach ($items as $it): ?>
        <tr>
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
              <?= he($it['price_prefix'] ?: 'Mulai Rp') ?> <?= number_format($it['price'], 0, ',', '.') ?>
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
              <button class="btn btn-secondary btn-sm"
                onclick='editItem(<?= htmlspecialchars(json_encode($it)) ?>)'>Edit</button>
              <a class="btn btn-danger btn-sm"
                href="layanan.php?action=del_item&id=<?= $it['id'] ?>&_token=<?= $token ?>"
                onclick="return confirm('Hapus layanan ini?')">Hapus</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
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
    <form method="POST" action="layanan.php">
      <input type="hidden" name="_token" value="<?= $token ?>">
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
      <div class="form-group">
        <label class="lbl">Urutan tampil</label>
        <input type="number" name="sort_order" value="0" min="0">
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
    <form method="POST" action="layanan.php">
      <input type="hidden" name="_token" value="<?= $token ?>">
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
      <div class="form-group">
        <label class="lbl">Urutan</label>
        <input type="number" name="sort_order" id="ec-sort" min="0">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-cat')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Add Item -->
<div class="modal-overlay" id="modal-add-item">
  <div class="modal">
    <div class="modal-header">
      <h3>Tambah Layanan</h3>
      <button class="modal-close" onclick="closeModal('modal-add-item')">×</button>
    </div>
    <form method="POST" action="layanan.php">
      <input type="hidden" name="_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="add_item">
      <div class="form-group">
        <label class="lbl">Kategori *</label>
        <select name="category_id" required>
          <option value="">— pilih kategori —</option>
          <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>"><?= he($c['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="lbl">Nama Layanan *</label>
        <input type="text" name="name" required placeholder="mis. Laser Rejuvenation" oninput="autoSlug(this,'slug-item-add')">
      </div>
      <div class="form-group">
        <label class="lbl">Slug</label>
        <input type="text" name="slug" id="slug-item-add" placeholder="laser-rejuvenation">
      </div>
      <div class="form-grid form-grid-2">
        <div class="form-group">
          <label class="lbl">Prefix Harga</label>
          <input type="text" name="price_prefix" value="Mulai Rp" placeholder="Mulai Rp">
        </div>
        <div class="form-group">
          <label class="lbl">Harga (angka)</label>
          <input type="number" name="price" value="0" min="0">
        </div>
      </div>
      <div class="form-group">
        <label class="lbl">Durasi</label>
        <input type="text" name="duration" placeholder="mis. 45 menit / sesi">
      </div>
      <div class="form-group">
        <label class="lbl">Deskripsi</label>
        <textarea name="description" rows="3" placeholder="Deskripsi singkat layanan…"></textarea>
      </div>
      <div class="form-group">
        <label class="lbl">URL Gambar</label>
        <input type="text" name="img" placeholder="assets/images/treatment-xxx.jpg">
        <p class="hint">Relatif dari root website, mis. <code>assets/images/treatment-laser.jpg</code></p>
      </div>
      <div class="form-group">
        <label class="lbl">Urutan</label>
        <input type="number" name="sort_order" value="0" min="0">
      </div>
      <div class="form-group">
        <label class="toggle-wrap">
          <span class="toggle"><input type="checkbox" name="is_active" checked><span class="slider"></span></span>
          Aktif (tampil di website)
        </label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-item')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Item -->
<div class="modal-overlay" id="modal-edit-item">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit Layanan</h3>
      <button class="modal-close" onclick="closeModal('modal-edit-item')">×</button>
    </div>
    <form method="POST" action="layanan.php">
      <input type="hidden" name="_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="edit_item">
      <input type="hidden" name="id" id="ei-id">
      <div class="form-group">
        <label class="lbl">Kategori *</label>
        <select name="category_id" id="ei-cat" required>
          <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>"><?= he($c['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="lbl">Nama Layanan *</label>
        <input type="text" name="name" id="ei-name" required>
      </div>
      <div class="form-group">
        <label class="lbl">Slug</label>
        <input type="text" name="slug" id="ei-slug">
      </div>
      <div class="form-grid form-grid-2">
        <div class="form-group">
          <label class="lbl">Prefix Harga</label>
          <input type="text" name="price_prefix" id="ei-prefix">
        </div>
        <div class="form-group">
          <label class="lbl">Harga (angka)</label>
          <input type="number" name="price" id="ei-price" min="0">
        </div>
      </div>
      <div class="form-group">
        <label class="lbl">Durasi</label>
        <input type="text" name="duration" id="ei-duration">
      </div>
      <div class="form-group">
        <label class="lbl">Deskripsi</label>
        <textarea name="description" id="ei-desc" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label class="lbl">URL Gambar</label>
        <input type="text" name="img" id="ei-img">
      </div>
      <div class="form-group">
        <label class="lbl">Urutan</label>
        <input type="number" name="sort_order" id="ei-sort" min="0">
      </div>
      <div class="form-group">
        <label class="toggle-wrap">
          <span class="toggle"><input type="checkbox" name="is_active" id="ei-active"><span class="slider"></span></span>
          Aktif (tampil di website)
        </label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-item')">Batal</button>
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

function editCat(cat) {
  document.getElementById('ec-id').value    = cat.id;
  document.getElementById('ec-label').value = cat.label;
  document.getElementById('ec-slug').value  = cat.slug;
  document.getElementById('ec-sort').value  = cat.sort_order;
  openModal('modal-edit-cat');
}

function editItem(it) {
  document.getElementById('ei-id').value       = it.id;
  document.getElementById('ei-cat').value      = it.category_id;
  document.getElementById('ei-name').value     = it.name;
  document.getElementById('ei-slug').value     = it.slug;
  document.getElementById('ei-prefix').value   = it.price_prefix || 'Mulai Rp';
  document.getElementById('ei-price').value    = it.price || 0;
  document.getElementById('ei-duration').value = it.duration || '';
  document.getElementById('ei-desc').value     = it.description || '';
  document.getElementById('ei-img').value      = it.img || '';
  document.getElementById('ei-sort').value     = it.sort_order || 0;
  document.getElementById('ei-active').checked = it.is_active == 1;
  openModal('modal-edit-item');
}
</script>

<?php admin_foot(); ?>
