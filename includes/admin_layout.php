<?php
/**
 * includes/admin_layout.php
 *
 * Shared helpers for all admin pages.
 * Usage: require_once __DIR__ . '/../includes/admin_layout.php';
 *        $active_page = 'layanan';
 *        admin_head('Page Title', $active_page);
 *        ... page content ...
 *        admin_foot();
 */

if (!function_exists('admin_head')) :

function admin_head(string $title, string $active_page = 'consultations'): void {
    $unread = 0;
    try {
        $unread = (int) db()->query("SELECT COUNT(*) FROM consultations WHERE is_read=0")->fetchColumn();
    } catch (Throwable $e) {}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?> · Uvora Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Cormorant+Garamond:wght@600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --gold:      #ab8f2c;
      --gold-lt:   #dfcdaf;
      --gold-pale: #faf6ee;
      --cream:     #faf8f4;
      --dark:      #1a1714;
      --muted:     #6b6459;
      --border:    #e6dfd1;
      --danger:    #dc2626;
      --success:   #16a34a;
      --radius:    12px;
      --sidebar-w: 230px;
    }
    html, body { height: 100%; }
    body { font-family: 'Inter', sans-serif; background: var(--cream); color: var(--dark); display: flex; min-height: 100svh; font-size: .875rem; }

    /* Sidebar */
    .sidebar { width: var(--sidebar-w); background: var(--dark); color: #e5e0d7; display: flex; flex-direction: column; padding: 1.5rem 1rem; flex-shrink: 0; position: fixed; top: 0; left: 0; bottom: 0; z-index: 10; overflow-y: auto; }
    .sidebar-logo { font-family: 'Cormorant Garamond', serif; font-size: 1.35rem; color: var(--gold-lt); letter-spacing: -.01em; padding: .25rem .5rem; margin-bottom: .25rem; }
    .sidebar-sub { font-size: .7rem; color: #7a7168; padding: 0 .5rem; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: .08em; }
    .nav-group-label { font-size: .65rem; color: #5a5349; text-transform: uppercase; letter-spacing: .1em; padding: .5rem .75rem .25rem; margin-top: .5rem; }
    .nav-item { display: flex; align-items: center; gap: .6rem; padding: .6rem .75rem; border-radius: 8px; text-decoration: none; color: #c4bdb5; font-size: .82rem; font-weight: 500; transition: background .15s, color .15s; margin-bottom: .1rem; }
    .nav-item:hover, .nav-item.active { background: rgba(171,143,44,.18); color: var(--gold-lt); }
    .nav-item svg { opacity: .7; flex-shrink: 0; }
    .nav-item.active svg { opacity: 1; }
    .sidebar-spacer { flex: 1; }
    .logout-btn { display: flex; align-items: center; gap: .6rem; padding: .6rem .75rem; border-radius: 8px; text-decoration: none; color: #9a8e85; font-size: .8rem; font-weight: 500; transition: background .15s, color .15s; margin-top: .5rem; }
    .logout-btn:hover { background: rgba(220,38,38,.12); color: #fca5a5; }

    /* Main */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100svh; }
    .topbar { background: #fff; border-bottom: 1px solid var(--border); padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; position: sticky; top: 0; z-index: 5; }
    .topbar h1 { font-size: 1rem; font-weight: 600; }
    .content { padding: 1.5rem; flex: 1; }

    /* Shared components */
    .badge { margin-left: auto; background: var(--gold); color: #fff; font-size: .65rem; font-weight: 700; padding: .15rem .45rem; border-radius: 999px; }
    .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: 8px; font-size: .82rem; font-weight: 500; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: background .15s, border-color .15s, color .15s; white-space: nowrap; }
    .btn-primary { background: var(--gold); color: #fff; border-color: var(--gold); }
    .btn-primary:hover { background: #9a7f28; }
    .btn-secondary { background: #fff; color: var(--dark); border-color: var(--border); }
    .btn-secondary:hover { border-color: var(--gold); color: var(--gold); }
    .btn-danger { background: #fee2e2; color: var(--danger); border-color: #fecaca; }
    .btn-danger:hover { background: #fecaca; }
    .btn-sm { padding: .3rem .65rem; font-size: .76rem; }

    /* Flash */
    .flash { padding: .75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: .85rem; display: flex; align-items: center; gap: .5rem; }
    .flash-ok  { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .flash-err { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    /* Card */
    .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); }
    .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: .75rem; flex-wrap: wrap; }
    .card-header h2 { font-size: .92rem; font-weight: 600; }
    .card-body { padding: 1.25rem; }

    /* Table */
    .tbl-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .82rem; }
    th { text-align: left; padding: .65rem .75rem; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 1px solid var(--border); white-space: nowrap; }
    td { padding: .7rem .75rem; border-bottom: 1px solid #f0ebe3; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #faf6ee; }

    /* Form elements */
    .form-grid { display: grid; gap: 1rem; }
    .form-grid-2 { grid-template-columns: 1fr 1fr; }
    label.lbl { font-size: .78rem; font-weight: 600; color: var(--muted); display: block; margin-bottom: .3rem; }
    input[type=text], input[type=url], input[type=number], textarea, select { padding: .55rem .8rem; border: 1px solid var(--border); border-radius: 8px; font-size: .85rem; font-family: inherit; background: #fff; transition: border-color .15s; width: 100%; }
    input:focus, textarea:focus, select:focus { outline: none; border-color: var(--gold); }
    textarea { resize: vertical; min-height: 90px; }
    .hint { font-size: .74rem; color: var(--muted); margin-top: .2rem; }
    .form-group { margin-bottom: .75rem; }

    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(26,23,20,.55); backdrop-filter: blur(2px); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 1rem; opacity: 0; pointer-events: none; transition: opacity .2s; }
    .modal-overlay.open { opacity: 1; pointer-events: all; }
    .modal { background: #fff; border-radius: 16px; width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto; padding: 1.5rem; transform: translateY(12px); transition: transform .2s; box-shadow: 0 20px 60px rgba(0,0,0,.2); }
    .modal-overlay.open .modal { transform: none; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
    .modal-header h3 { font-size: 1rem; font-weight: 600; }
    .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1.4rem; line-height: 1; padding: .25rem; }
    .modal-close:hover { color: var(--dark); }
    .modal-footer { display: flex; gap: .75rem; justify-content: flex-end; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border); }

    /* Toggle */
    .toggle-wrap { display: flex; align-items: center; gap: .5rem; }
    .toggle { position: relative; width: 36px; height: 20px; flex-shrink: 0; }
    .toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
    .slider { position: absolute; inset: 0; cursor: pointer; background: #d1cdc7; border-radius: 999px; transition: background .2s; }
    .slider::before { content: ''; position: absolute; height: 14px; width: 14px; left: 3px; top: 3px; background: #fff; border-radius: 50%; transition: transform .2s; }
    .toggle input:checked + .slider { background: var(--gold); }
    .toggle input:checked + .slider::before { transform: translateX(16px); }

    /* Misc */
    .img-preview { width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border); }
    .toolbar { display: flex; gap: .75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1rem; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--muted); }
    .empty-state svg { opacity: .3; margin-bottom: .75rem; }
    .tag { display: inline-block; padding: .2rem .6rem; border-radius: 999px; font-size: .72rem; font-weight: 600; }
    .tag-active { background: #dcfce7; color: #15803d; }
    .tag-inactive { background: #f3f4f6; color: #6b7280; }

    /* Search + filter tabs (dipakai Konsultasi & Layanan) */
    .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 360px; }
    .search-wrap > svg { position: absolute; left: .8rem; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
    .search-input { width: 100%; padding: .6rem .9rem .6rem 2.4rem; border: 1px solid var(--border); border-radius: 10px; font-size: .85rem; font-family: inherit; background: #fff; color: var(--dark); transition: border-color .15s, box-shadow .15s; -webkit-appearance: none; appearance: none; }
    .search-input::placeholder { color: #a39b8f; }
    .search-input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(171,143,44,.15); }
    .filter-tabs { display: flex; gap: .4rem; flex-wrap: wrap; align-items: center; }
    .filter-tab { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .95rem; border-radius: 999px; font-size: .8rem; font-weight: 500; text-decoration: none; border: 1px solid var(--border); background: #fff; color: var(--muted); cursor: pointer; font-family: inherit; transition: background .15s, border-color .15s, color .15s; white-space: nowrap; }
    .filter-tab:hover { border-color: var(--gold); color: var(--gold); }
    .filter-tab.active { background: var(--gold); border-color: var(--gold); color: #fff; }
    .filter-tab .count { font-size: .7rem; font-weight: 600; padding: .05rem .4rem; border-radius: 999px; background: rgba(0,0,0,.07); }
    .filter-tab.active .count { background: rgba(255,255,255,.25); }
    .table-wrap { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); overflow-x: auto; }
    .img-missing { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 6px; border: 1px dashed #e0b4b4; background: #fef2f2; color: var(--danger); font-size: .6rem; font-weight: 600; text-align: center; line-height: 1.15; }
  </style>
  <script>
    // Gambar rusak (file upload hilang) → tampilkan penanda jelas, bukan ikon pecah
    document.addEventListener('error', function (e) {
      var t = e.target;
      if (!t || t.tagName !== 'IMG' || !t.classList.contains('img-preview')) return;
      var s = document.createElement('span');
      s.className = 'img-missing'; s.textContent = 'File hilang'; s.title = t.getAttribute('src');
      t.replaceWith(s);
    }, true);
  </script>
</head>
<body>

<nav class="sidebar">
  <p class="sidebar-logo">Uvora Clinic</p>
  <p class="sidebar-sub">Admin Panel</p>

  <p class="nav-group-label">Inbox</p>
  <a href="index.php" class="nav-item <?= $active_page === 'consultations' ? 'active' : '' ?>">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
    Konsultasi
    <?php if ($unread > 0): ?><span class="badge"><?= $unread ?></span><?php endif; ?>
  </a>

  <p class="nav-group-label">CMS</p>
  <a href="layanan.php" class="nav-item <?= $active_page === 'layanan' ? 'active' : '' ?>">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M19 11H5M19 11a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2M19 11V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2m-6 0h6"/></svg>
    Layanan
  </a>
  <a href="promos.php" class="nav-item <?= $active_page === 'promos' ? 'active' : '' ?>">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 14.25 15 9.75M9 10h.01M15 14h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
    Promo
  </a>
  <a href="hasil.php" class="nav-item <?= $active_page === 'hasil' ? 'active' : '' ?>">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 5h8v14H3zM13 5h8v14h-8z"/></svg>
    Hasil (Before/After)
  </a>
  <a href="content.php" class="nav-item <?= $active_page === 'content' ? 'active' : '' ?>">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
    Dokter & Konten
  </a>

  <div class="sidebar-spacer"></div>

  <a href="../index.php" target="_blank" class="nav-item" style="font-size:.78rem;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
    Lihat Website
  </a>
  <a href="akun.php" class="nav-item <?= $active_page === 'akun' ? 'active' : '' ?>" style="font-size:.78rem;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/></svg>
    Akun
  </a>
  <a href="index.php?action=logout" class="logout-btn">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
    Keluar
  </a>
</nav>

<div class="main">
  <header class="topbar">
    <h1><?= htmlspecialchars($title) ?></h1>
    <span style="font-size:.78rem;color:var(--muted);">Uvora Clinic Admin</span>
  </header>
  <div class="content">
<?php
}

function admin_foot(): void { ?>
  </div>
</div>

<!-- ── Shared Confirm-Delete Modal ─────────────────────────────────── -->
<div class="modal-overlay" id="modal-confirm-delete" style="z-index:200">
  <div class="modal" style="max-width:400px">
    <div class="modal-header">
      <h3 id="modal-confirm-title">Hapus item ini?</h3>
      <button class="modal-close" onclick="closeConfirm()">×</button>
    </div>
    <p id="modal-confirm-body" style="font-size:.85rem;color:var(--muted);margin-bottom:1.25rem">Tindakan ini tidak dapat dibatalkan.</p>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeConfirm()">Batal</button>
      <a id="modal-confirm-href" href="#" class="btn btn-danger">Ya, Hapus</a>
    </div>
  </div>
</div>
<script>
function confirmDelete(qs, label) {
  // qs may be a full URL or just "?action=..." — resolve to absolute path
  var href = qs.startsWith('?') ? window.location.pathname + qs : qs;
  var m = document.getElementById('modal-confirm-delete');
  document.getElementById('modal-confirm-title').textContent = label ? ('Hapus ' + label + '?') : 'Hapus item ini?';
  document.getElementById('modal-confirm-body').textContent  = 'Tindakan ini tidak dapat dibatalkan.';
  document.getElementById('modal-confirm-href').href = href;
  m.classList.add('open');
}
function closeConfirm() {
  document.getElementById('modal-confirm-delete').classList.remove('open');
}
document.getElementById('modal-confirm-delete').addEventListener('click', function(e) {
  if (e.target === this) closeConfirm();
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeConfirm();
});
</script>
</body>
</html>
<?php }

function flash_set(string $type, string $msg): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['_flash'] = ['type' => $type, 'msg' => $msg];
}

function flash_html(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['_flash'])) return;
    $f = $_SESSION['_flash']; unset($_SESSION['_flash']);
    $cls  = $f['type'] === 'ok' ? 'flash-ok' : 'flash-err';
    $icon = $f['type'] === 'ok' ? '✓' : '✕';
    echo '<div class="flash ' . $cls . '">' . $icon . ' ' . htmlspecialchars($f['msg']) . '</div>';
}

function make_slug(string $str): string {
    $str = mb_strtolower(trim($str));
    $str = preg_replace('/[\s\-]+/', '-', $str);
    $str = preg_replace('/[^a-z0-9\-]/', '', $str);
    return trim($str, '-') ?: 'item-' . time();
}

function he(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

endif;
