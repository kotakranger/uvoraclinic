<?php
/**
 * admin/content.php — CMS untuk Dokter, Testimoni, Content Reels
 *
 * Tabs: doctors | testimonials | reels
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/uploader.php';
require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$pdo    = db();
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$tab    = in_array($_GET['tab'] ?? '', ['doctors','testimonials','reels']) ? $_GET['tab'] : 'doctors';
$token  = csrf_token();

// ══════════════════ POST HANDLERS ══════════════════

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {

    // ── DOCTORS ──────────────────────────────────────────────────────────
    if ($action === 'add_doctor') {
        $name     = trim($_POST['name'] ?? '');
        $role     = trim($_POST['role'] ?? '');
        try { $img = handle_image_field('img', 'doctors', $_POST['img'] ?? ''); }
        catch (RuntimeException $e) { flash_set('err', $e->getMessage()); header('Location: content.php?tab=doctors'); exit; }
        $schedule = trim($_POST['schedule'] ?? '');
        $exp_raw  = trim($_POST['expertise'] ?? '');
        $exp_json = $exp_raw ? json_encode(array_values(array_filter(array_map('trim', explode("\n", $exp_raw))))) : null;
        $sort     = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),-1)+1 FROM doctors")->fetchColumn();
        $active   = isset($_POST['is_active']) ? 1 : 0;
        if ($name) {
            $pdo->prepare("INSERT INTO doctors (name, role, img, schedule, expertise_json, sort_order, is_active) VALUES (?,?,?,?,?,?,?)")
                ->execute([$name, $role, $img, $schedule, $exp_json, $sort, $active]);
            flash_set('ok', "Dokter \"{$name}\" ditambahkan.");
        }
        header("Location: content.php?tab=doctors"); exit;
    }

    if ($action === 'edit_doctor') {
        $id       = (int)($_POST['id'] ?? 0);
        $name     = trim($_POST['name'] ?? '');
        $role     = trim($_POST['role'] ?? '');
        try { $img = handle_image_field('img', 'doctors', $_POST['img'] ?? ''); }
        catch (RuntimeException $e) { flash_set('err', $e->getMessage()); header('Location: content.php?tab=doctors'); exit; }
        $schedule = trim($_POST['schedule'] ?? '');
        $exp_raw  = trim($_POST['expertise'] ?? '');
        $exp_json = $exp_raw ? json_encode(array_values(array_filter(array_map('trim', explode("\n", $exp_raw))))) : null;
        $active   = isset($_POST['is_active']) ? 1 : 0;
        if ($id && $name) {
            $pdo->prepare("UPDATE doctors SET name=?, role=?, img=?, schedule=?, expertise_json=?, is_active=? WHERE id=?")
                ->execute([$name, $role, $img, $schedule, $exp_json, $active, $id]);
            flash_set('ok', 'Dokter diperbarui.');
        }
        header("Location: content.php?tab=doctors"); exit;
    }

    // ── TESTIMONIALS ──────────────────────────────────────────────────────
    if ($action === 'add_testimonial') {
        $quote  = trim($_POST['quote'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $city   = trim($_POST['city'] ?? '');
        $sort   = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),-1)+1 FROM testimonials")->fetchColumn();
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($quote && $author) {
            $pdo->prepare("INSERT INTO testimonials (quote, author, city, sort_order, is_active) VALUES (?,?,?,?,?)")
                ->execute([$quote, $author, $city, $sort, $active]);
            flash_set('ok', "Testimoni dari \"{$author}\" ditambahkan.");
        }
        header("Location: content.php?tab=testimonials"); exit;
    }

    if ($action === 'edit_testimonial') {
        $id     = (int)($_POST['id'] ?? 0);
        $quote  = trim($_POST['quote'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $city   = trim($_POST['city'] ?? '');
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($id) {
            $pdo->prepare("UPDATE testimonials SET quote=?, author=?, city=?, is_active=? WHERE id=?")
                ->execute([$quote, $author, $city, $active, $id]);
            flash_set('ok', 'Testimoni diperbarui.');
        }
        header("Location: content.php?tab=testimonials"); exit;
    }

    // ── REELS ─────────────────────────────────────────────────────────────
    if ($action === 'add_reel') {
        $url    = trim($_POST['embed_url'] ?? '');
        $sort   = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),-1)+1 FROM content_reels")->fetchColumn();
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($url) {
            $pdo->prepare("INSERT INTO content_reels (embed_url, sort_order, is_active) VALUES (?,?,?)")
                ->execute([$url, $sort, $active]);
            flash_set('ok', 'Reel ditambahkan.');
        }
        header("Location: content.php?tab=reels"); exit;
    }

    if ($action === 'edit_reel') {
        $id     = (int)($_POST['id'] ?? 0);
        $url    = trim($_POST['embed_url'] ?? '');
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($id) {
            $pdo->prepare("UPDATE content_reels SET embed_url=?, is_active=? WHERE id=?")
                ->execute([$url, $active, $id]);
            flash_set('ok', 'Reel diperbarui.');
        }
        header("Location: content.php?tab=reels"); exit;
    }
}

// ── GET actions ────────────────────────────────────────────────────────────
$delMap = [
    'del_doctor'      => ['doctors',      "content.php?tab=doctors"],
    'del_testimonial' => ['testimonials', "content.php?tab=testimonials"],
    'del_reel'        => ['content_reels',"content.php?tab=reels"],
];
if (isset($delMap[$action]) && isset($_GET['id']) && csrf_verify()) {
    [$table, $redirect] = $delMap[$action];
    $pdo->prepare("DELETE FROM {$table} WHERE id=?")->execute([(int)$_GET['id']]);
    flash_set('ok', 'Item dihapus.');
    header("Location: {$redirect}"); exit;
}

$toggleMap = [
    'toggle_doctor'      => ['doctors',       "doctors"],
    'toggle_testimonial' => ['testimonials',  "testimonials"],
    'toggle_reel'        => ['content_reels', "reels"],
];
if (isset($toggleMap[$action]) && isset($_GET['id']) && csrf_verify()) {
    [$table, $t] = $toggleMap[$action];
    $pdo->prepare("UPDATE {$table} SET is_active = 1 - is_active WHERE id=?")->execute([(int)$_GET['id']]);
    header("Location: content.php?tab={$t}"); exit;
}

// ── Fetch ──────────────────────────────────────────────────────────────────
$doctors      = $pdo->query("SELECT * FROM doctors ORDER BY sort_order, name")->fetchAll();
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order, id")->fetchAll();
$reels        = $pdo->query("SELECT * FROM content_reels ORDER BY sort_order, id")->fetchAll();

$active_page = 'content';
admin_head('Dokter & Konten', $active_page);
?>

<?php flash_html(); ?>

<!-- Tab navigation -->
<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--border);padding-bottom:0;">
  <?php
  $tabs = ['doctors' => 'Dokter', 'testimonials' => 'Testimoni', 'reels' => 'Content Reels'];
  foreach ($tabs as $k => $label): ?>
  <a href="content.php?tab=<?= $k ?>" style="
    padding:.55rem 1.1rem;
    text-decoration:none;
    font-size:.83rem;
    font-weight:500;
    border-radius:8px 8px 0 0;
    border: 1px solid <?= $tab === $k ? 'var(--border)' : 'transparent' ?>;
    border-bottom-color: <?= $tab === $k ? '#fff' : 'transparent' ?>;
    background: <?= $tab === $k ? '#fff' : 'transparent' ?>;
    color: <?= $tab === $k ? 'var(--gold)' : 'var(--muted)' ?>;
    margin-bottom: -1px;
  "><?= $label ?></a>
  <?php endforeach; ?>
</div>

<!-- ══════════ DOCTORS TAB ══════════ -->
<?php if ($tab === 'doctors'): ?>

<div class="toolbar">
  <span style="font-size:.85rem;color:var(--muted);"><?= count($doctors) ?> dokter</span>
  <button class="btn btn-primary btn-sm" style="margin-left:auto;" onclick="openModal('modal-add-doctor')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    Tambah Dokter
  </button>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Foto</th><th>Nama</th><th>Jabatan</th><th>Jadwal</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php if (empty($doctors)): ?>
        <tr><td colspan="6" class="empty-state">Belum ada dokter.</td></tr>
      <?php else: ?>
      <?php foreach ($doctors as $d): ?>
        <tr>
          <td><?php if ($d['img']): ?><img src="../<?= he($d['img']) ?>" class="img-preview" alt=""><?php else: ?>—<?php endif; ?></td>
          <td><strong><?= he($d['name']) ?></strong></td>
          <td><?= he($d['role'] ?: '—') ?></td>
          <td style="font-size:.78rem;"><?= he($d['schedule'] ?: '—') ?></td>
          <td>
            <a href="content.php?tab=doctors&action=toggle_doctor&id=<?= $d['id'] ?>&csrf_token=<?= $token ?>">
              <span class="tag <?= $d['is_active'] ? 'tag-active' : 'tag-inactive' ?>"><?= $d['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
            </a>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <button class="btn btn-secondary btn-sm" onclick='editDoctor(<?= htmlspecialchars(json_encode($d)) ?>)'>Edit</button>
              <button type="button" class="btn btn-danger btn-sm" onclick='confirmDelete("?tab=doctors&action=del_doctor&id=<?= $d["id"] ?>&csrf_token=<?= $token ?>", "dokter")'>Hapus</button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Doctor -->
<div class="modal-overlay" id="modal-add-doctor">
  <div class="modal">
    <div class="modal-header"><h3>Tambah Dokter</h3><button class="modal-close" onclick="closeModal('modal-add-doctor')">×</button></div>
    <form method="POST" action="content.php?tab=doctors" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="add_doctor">
      <?php include __DIR__ . '/partials/_doctor_form.php'; ?>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-doctor')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Doctor -->
<div class="modal-overlay" id="modal-edit-doctor">
  <div class="modal">
    <div class="modal-header"><h3>Edit Dokter</h3><button class="modal-close" onclick="closeModal('modal-edit-doctor')">×</button></div>
    <form method="POST" action="content.php?tab=doctors" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="edit_doctor">
      <input type="hidden" name="id" id="ed-id">
      <?php include __DIR__ . '/partials/_doctor_form.php'; ?>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-doctor')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?php endif; ?>

<!-- ══════════ TESTIMONIALS TAB ══════════ -->
<?php if ($tab === 'testimonials'): ?>

<div class="toolbar">
  <span style="font-size:.85rem;color:var(--muted);"><?= count($testimonials) ?> testimoni</span>
  <button class="btn btn-primary btn-sm" style="margin-left:auto;" onclick="openModal('modal-add-testi')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    Tambah Testimoni
  </button>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Kutipan</th><th>Nama</th><th>Kota</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php if (empty($testimonials)): ?>
        <tr><td colspan="5" class="empty-state">Belum ada testimoni.</td></tr>
      <?php else: ?>
      <?php foreach ($testimonials as $t): ?>
        <tr>
          <td style="max-width:300px;">
            <span style="font-style:italic;color:var(--muted);">
              "<?= he(mb_substr($t['quote'], 0, 90)) . (mb_strlen($t['quote']) > 90 ? '…' : '') ?>"
            </span>
          </td>
          <td><strong><?= he($t['author']) ?></strong></td>
          <td><?= he($t['city'] ?: '—') ?></td>
          <td>
            <a href="content.php?tab=testimonials&action=toggle_testimonial&id=<?= $t['id'] ?>&csrf_token=<?= $token ?>">
              <span class="tag <?= $t['is_active'] ? 'tag-active' : 'tag-inactive' ?>"><?= $t['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
            </a>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <button class="btn btn-secondary btn-sm" onclick='editTesti(<?= htmlspecialchars(json_encode($t)) ?>)'>Edit</button>
              <button type="button" class="btn btn-danger btn-sm" onclick='confirmDelete("?tab=testimonials&action=del_testimonial&id=<?= $t["id"] ?>&csrf_token=<?= $token ?>", "testimoni")'>Hapus</button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Testimonial -->
<div class="modal-overlay" id="modal-add-testi">
  <div class="modal">
    <div class="modal-header"><h3>Tambah Testimoni</h3><button class="modal-close" onclick="closeModal('modal-add-testi')">×</button></div>
    <form method="POST" action="content.php?tab=testimonials">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="add_testimonial">
      <?php include __DIR__ . '/partials/_testi_form.php'; ?>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-testi')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Testimonial -->
<div class="modal-overlay" id="modal-edit-testi">
  <div class="modal">
    <div class="modal-header"><h3>Edit Testimoni</h3><button class="modal-close" onclick="closeModal('modal-edit-testi')">×</button></div>
    <form method="POST" action="content.php?tab=testimonials">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="edit_testimonial">
      <input type="hidden" name="id" id="et-id">
      <?php include __DIR__ . '/partials/_testi_form.php'; ?>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-testi')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?php endif; ?>

<!-- ══════════ REELS TAB ══════════ -->
<?php if ($tab === 'reels'): ?>

<div class="toolbar">
  <span style="font-size:.85rem;color:var(--muted);"><?= count($reels) ?> reel</span>
  <button class="btn btn-primary btn-sm" style="margin-left:auto;" onclick="openModal('modal-add-reel')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    Tambah Reel
  </button>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>Embed URL</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php if (empty($reels)): ?>
        <tr><td colspan="5" class="empty-state">Belum ada reel.</td></tr>
      <?php else: ?>
      <?php foreach ($reels as $i => $r): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td>
            <span style="font-size:.75rem;color:var(--muted);word-break:break-all;">
              <?= he(mb_substr($r['embed_url'], 0, 60)) . (mb_strlen($r['embed_url']) > 60 ? '…' : '') ?>
            </span>
          </td>
          <td>
            <a href="content.php?tab=reels&action=toggle_reel&id=<?= $r['id'] ?>&csrf_token=<?= $token ?>">
              <span class="tag <?= $r['is_active'] ? 'tag-active' : 'tag-inactive' ?>"><?= $r['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
            </a>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;">
              <button class="btn btn-secondary btn-sm" onclick='editReel(<?= htmlspecialchars(json_encode($r)) ?>)'>Edit</button>
              <button type="button" class="btn btn-danger btn-sm" onclick='confirmDelete("?tab=reels&action=del_reel&id=<?= $r["id"] ?>&csrf_token=<?= $token ?>", "reel")'>Hapus</button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Reel -->
<div class="modal-overlay" id="modal-add-reel">
  <div class="modal">
    <div class="modal-header"><h3>Tambah Content Reel</h3><button class="modal-close" onclick="closeModal('modal-add-reel')">×</button></div>
    <form method="POST" action="content.php?tab=reels">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="add_reel">
      <div class="form-group">
        <label class="lbl">URL Video *</label>
        <input type="url" name="embed_url" required placeholder="https://www.tiktok.com/@account/video/123 atau https://www.instagram.com/p/ABC">
        <div class="hint" style="line-height:1.7;margin-top:.4rem">
          Format URL yang didukung:<br>
          🎵 <strong>TikTok</strong>: <code>https://www.tiktok.com/@namaakun/video/VIDEO_ID</code><br>
          📸 <strong>Instagram</strong>: <code>https://www.instagram.com/p/SHORTCODE/</code> atau <code>/reel/SHORTCODE/</code><br>
          ▶️ <strong>YouTube</strong>: <code>https://www.youtube.com/watch?v=VIDEO_ID</code> atau <code>/shorts/VIDEO_ID</code><br>
          <em>Gunakan URL biasa — sistem otomatis mendeteksi platform.</em>
        </div>
      </div>
      <div class="form-group">
        <label class="toggle-wrap">
          <span class="toggle"><input type="checkbox" name="is_active" checked><span class="slider"></span></span>
          Aktif
        </label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-reel')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Reel -->
<div class="modal-overlay" id="modal-edit-reel">
  <div class="modal">
    <div class="modal-header"><h3>Edit Reel</h3><button class="modal-close" onclick="closeModal('modal-edit-reel')">×</button></div>
    <form method="POST" action="content.php?tab=reels">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="action" value="edit_reel">
      <input type="hidden" name="id" id="er-id">
      <div class="form-group">
        <label class="lbl">URL Video *</label>
        <input type="url" name="embed_url" id="er-url" required>
        <div class="hint" style="line-height:1.7;margin-top:.4rem">
          🎵 TikTok: <code>https://www.tiktok.com/@akun/video/ID</code><br>
          📸 Instagram: <code>https://www.instagram.com/p/SHORTCODE/</code><br>
          ▶️ YouTube: <code>https://www.youtube.com/watch?v=ID</code>
        </div>
      </div>
      <div class="form-group">
        <label class="toggle-wrap">
          <span class="toggle"><input type="checkbox" name="is_active" id="er-active"><span class="slider"></span></span>
          Aktif
        </label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-reel')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?php endif; ?>

<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(function(o) {
  o.addEventListener('click', function(e) { if (e.target === o) o.classList.remove('open'); });
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(function(o){ o.classList.remove('open'); });
});

function editDoctor(d) {
  document.getElementById('ed-id').value = d.id;
  var m = document.getElementById('modal-edit-doctor');
  m.querySelector('[name=name]').value      = d.name || '';
  m.querySelector('[name=role]').value      = d.role || '';
  m.querySelector('[name=img]').value       = d.img || '';
  uvImgSync(m.querySelector('[name=img]'));
  m.querySelector('[name=schedule]').value  = d.schedule || '';
  var exp = [];
  try { exp = JSON.parse(d.expertise_json || '[]'); } catch(e){}
  m.querySelector('[name=expertise]').value = exp.join('\n');
  m.querySelector('[name=is_active]').checked = d.is_active == 1;
  openModal('modal-edit-doctor');
}

function editTesti(t) {
  document.getElementById('et-id').value = t.id;
  var m = document.getElementById('modal-edit-testi');
  m.querySelector('[name=quote]').value      = t.quote || '';
  m.querySelector('[name=author]').value     = t.author || '';
  m.querySelector('[name=city]').value       = t.city || '';
  m.querySelector('[name=is_active]').checked = t.is_active == 1;
  openModal('modal-edit-testi');
}

function editReel(r) {
  document.getElementById('er-id').value   = r.id;
  document.getElementById('er-url').value  = r.embed_url || '';
  document.getElementById('er-active').checked = r.is_active == 1;
  openModal('modal-edit-reel');
}
</script>

<?php admin_foot(); ?>
