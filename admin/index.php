<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/data.php';

admin_require_login();
ensure_consultations_schema();

// Handle actions
$action = $_GET['action'] ?? '';

// Mark as read
if ($action === 'read' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    db()->prepare("UPDATE consultations SET is_read = 1 WHERE id = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}

// Mark as unread
if ($action === 'unread' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    db()->prepare("UPDATE consultations SET is_read = 0 WHERE id = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}

// Delete single
if ($action === 'delete' && isset($_GET['id']) && csrf_verify()) {
    $id = (int) $_GET['id'];
    db()->prepare("DELETE FROM consultations WHERE id = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}

// Logout
if ($action === 'logout') {
    admin_logout();
    header('Location: login.php');
    exit;
}

// ── Filters & pagination ──────────────────────────────────────────────────
$filter   = $_GET['filter'] ?? 'all';   // all | unread
$search   = trim($_GET['q'] ?? '');
$page     = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 20;
$offset   = ($page - 1) * $per_page;

$where  = [];
$params = [];

if ($filter === 'unread') {
    $where[]  = 'is_read = 0';
}
if ($search !== '') {
    $where[]  = "(name LIKE ? OR phone LIKE ? OR treatment LIKE ? OR message LIKE ?)";
    $s = '%' . $search . '%';
    array_push($params, $s, $s, $s, $s);
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$pdo   = db();

// Total count for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM consultations $whereSQL");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM consultations $whereSQL ORDER BY id DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$rows = $stmt->fetchAll();

$total_pages = max(1, (int) ceil($total / $per_page));

// Summary stats
$stats = $pdo->query("
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today
    FROM consultations
")->fetch();

$token = csrf_token();

// ── Helper ────────────────────────────────────────────────────────────────
/** Nomor lokal (08xx) → format internasional untuk wa.me (62xx). */
function wa_number(string $p): string { $d = preg_replace('/\D/', '', $p); return $d !== '' && $d[0] === '0' ? '62' . substr($d, 1) : $d; }
function e2($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function qstr(array $extra = []): string {
    $q = array_merge([
        'filter' => $_GET['filter'] ?? 'all',
        'q'      => $_GET['q']      ?? '',
    ], $extra);
    return '?' . http_build_query(array_filter($q, fn($v) => $v !== ''));
}
$active_page = 'consultations';
?>
<?php admin_head('Form Konsultasi', $active_page); ?>
<?php /* Consultation-page-specific styles */ ?>
<style>
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
  .stat-card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); padding: 1rem 1.25rem; }
  .stat-label { font-size: .72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }
  .stat-value { font-size: 2rem; font-weight: 700; line-height: 1.1; margin-top: .25rem; }
  .stat-value.gold { color: var(--gold); }
  .toolbar { display: flex; gap: .75rem; flex-wrap: wrap; align-items: center; margin-bottom: 1rem; }
  .search-box { flex: 1; min-width: 160px; max-width: 300px; padding: .5rem .85rem; border: 1px solid var(--border); border-radius: 8px; font-size: .85rem; font-family: inherit; }
  .search-box:focus { outline: none; border-color: var(--gold); }
  .filter-btn { padding: .45rem .9rem; border-radius: 8px; font-size: .8rem; font-weight: 500; text-decoration: none; border: 1px solid var(--border); background: #fff; color: var(--dark); transition: all .15s; cursor: pointer; }
  .filter-btn.active, .filter-btn:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-pale); }
  .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
  .tbl-wrap { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; font-size: .82rem; }
  th { text-align: left; padding: .65rem .75rem; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 1px solid var(--border); white-space: nowrap; }
  td { padding: .7rem .75rem; border-bottom: 1px solid #f0ebe3; vertical-align: middle; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: var(--gold-pale); }
  .unread-row td { font-weight: 500; background: #fefdf9; }
  .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .9rem; border-radius: 8px; font-size: .8rem; font-weight: 500; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all .15s; white-space: nowrap; }
  .btn-sm { padding: .3rem .65rem; font-size: .76rem; }
  .btn-primary { background: var(--gold); color: #fff; border-color: var(--gold); }
  .btn-primary:hover { background: #9a7f28; }
  .btn-read { background: #fff; color: var(--muted); border-color: var(--border); }
  .btn-read:hover { border-color: var(--gold); color: var(--gold); }
  .btn-danger { background: #fee2e2; color: var(--danger); border-color: #fecaca; }
  .btn-danger:hover { background: #fecaca; }
  .pagination { display: flex; align-items: center; justify-content: center; gap: .35rem; margin-top: 1.25rem; flex-wrap: wrap; }
  .page-btn { padding: .4rem .75rem; border: 1px solid var(--border); border-radius: 8px; text-decoration: none; font-size: .8rem; color: var(--dark); transition: all .15s; }
  .page-btn:hover, .page-btn.active { border-color: var(--gold); color: var(--gold); background: var(--gold-pale); }
  .page-btn.disabled { opacity: .4; pointer-events: none; }
  .empty-state { text-align: center; padding: 3rem 1rem; color: var(--muted); }
  .treatment-pill { display: inline-block; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: .2rem .65rem; border-radius: 999px; background: var(--gold-pale); border: 1px solid var(--gold-lt); color: #7a6420; font-size: .76rem; font-weight: 500; vertical-align: middle; }
  .message-cell { max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--muted); }
  .badge-unread { display: inline-block; margin-left: .35rem; padding: .1rem .5rem; border-radius: 999px; background: var(--gold); color: #fff; font-size: .62rem; font-weight: 700; letter-spacing: .03em; vertical-align: middle; }
  .actions { display: flex; gap: .35rem; align-items: center; }
  .actions .btn-sm { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .65rem; border-radius: 8px; font-size: .76rem; font-weight: 500; text-decoration: none; border: 1px solid var(--border); cursor: pointer; }
  .btn-whatsapp { background: #25D366; border-color: #25D366 !important; color: #fff; }
  .btn-whatsapp:hover { background: #1fb558; }
  @media (max-width: 768px) { .hide-sm { display: none; } }
</style>


    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <p class="stat-label">Total</p>
        <p class="stat-value"><?= (int)$stats['total'] ?></p>
      </div>
      <div class="stat-card">
        <p class="stat-label">Belum Dibaca</p>
        <p class="stat-value gold"><?= (int)$stats['unread'] ?></p>
      </div>
      <div class="stat-card">
        <p class="stat-label">Hari Ini</p>
        <p class="stat-value"><?= (int)$stats['today'] ?></p>
      </div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
      <form method="GET" action="index.php" style="display:contents">
        <input type="hidden" name="filter" value="<?= e2($filter) ?>">
        <div class="search-wrap">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input class="search-input" type="search" name="q" placeholder="Cari nama, telepon, perawatan…"
                 value="<?= e2($search) ?>" autocomplete="off">
        </div>
      </form>

      <div class="filter-tabs">
        <a href="<?= qstr(['filter' => 'all',    'page' => 1]) ?>" class="filter-tab <?= $filter === 'all'    ? 'active' : '' ?>">Semua</a>
        <a href="<?= qstr(['filter' => 'unread', 'page' => 1]) ?>" class="filter-tab <?= $filter === 'unread' ? 'active' : '' ?>">Belum dibaca <?= $stats['unread'] > 0 ? "({$stats['unread']})" : '' ?></a>
      </div>
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <?php if (empty($rows)): ?>
        <div class="empty-state">
          <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
          <p>Belum ada form konsultasi masuk.</p>
        </div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th class="hide-sm">No. WhatsApp</th>
              <th>Perawatan</th>
              <th>Jadwal Kunjungan</th>
              <th class="hide-sm">Keluhan</th>
              <th class="hide-sm">Waktu</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
            <tr class="<?= !$row['is_read'] ? 'unread' : '' ?>">
              <td style="color:var(--muted);font-size:.75rem"><?= (int)$row['id'] ?></td>
              <td>
                <?= e2($row['name']) ?>
                <?php if (!$row['is_read']): ?>
                  <span class="badge-unread">Baru</span>
                <?php endif; ?>
              </td>
              <td class="hide-sm">
                <a href="https://wa.me/<?= e2(wa_number($row['phone'])) ?>" target="_blank" rel="noopener"
                   style="color:var(--gold);text-decoration:none;">
                  <?= e2($row['phone']) ?>
                </a>
              </td>
              <td><span class="treatment-pill" title="<?= e2($row['treatment']) ?>"><?= e2($row['treatment']) ?></span></td>
              <td style="white-space:nowrap;font-size:.8rem;">
                <?php if (!empty($row['visit_date'])):
                  $vd = new DateTime($row['visit_date']);
                  $hari = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'][(int)$vd->format('w')];
                  $is_today = $vd->format('Y-m-d') === date('Y-m-d');
                ?>
                  <strong><?= $hari . ', ' . $vd->format('d-m-Y') ?></strong><?= $is_today ? ' <span class="badge-unread">Hari ini</span>' : '' ?>
                  <div style="color:var(--muted);"><?= e2(str_replace(':', '.', (string)$row['visit_time'])) ?> WIB</div>
                <?php else: ?><em style="opacity:.4">—</em><?php endif; ?>
              </td>
              <td class="hide-sm message-cell" title="<?= e2($row['message']) ?>">
                <?= $row['message'] ? e2($row['message']) : '<em style="opacity:.4">—</em>' ?>
              </td>
              <td class="hide-sm" style="color:var(--muted);white-space:nowrap;font-size:.78rem"><?= e2($row['created_at']) ?></td>
              <td>
                <div class="actions">
                  <!-- WhatsApp -->
                  <?php
                    $wa_jadwal = !empty($row['visit_date']) ? ' pada ' . date('d-m-Y', strtotime($row['visit_date'])) . ' pukul ' . str_replace(':', '.', (string)$row['visit_time']) . ' WIB' : '';
                    $wa_text = "Halo " . $row['name'] . ", kami dari Uvora Clinic. Menindaklanjuti permintaan konsultasi Anda mengenai " . $row['treatment'] . $wa_jadwal . ". Kami konfirmasi jadwal tersebut ya?";
                    $wa_link = 'https://wa.me/' . wa_number($row['phone']) . '?text=' . rawurlencode($wa_text);
                  ?>
                  <a href="<?= e2($wa_link) ?>" target="_blank" rel="noopener" class="btn-sm btn-whatsapp" title="Balas via WhatsApp">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    Balas
                  </a>

                  <!-- Mark read / unread -->
                  <?php if (!$row['is_read']): ?>
                    <a href="index.php?action=read&id=<?= (int)$row['id'] ?>" class="btn-sm btn-read" title="Tandai sudah dibaca">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                    </a>
                  <?php else: ?>
                    <a href="index.php?action=unread&id=<?= (int)$row['id'] ?>" class="btn-sm btn-read" title="Tandai belum dibaca">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
                    </a>
                  <?php endif; ?>

                  <!-- Delete -->
                  <a href="index.php?action=delete&id=<?= (int)$row['id'] ?>&csrf_token=<?= urlencode($token) ?>"
                     class="btn-sm btn-danger"
                     title="Hapus"
                     onclick="return confirm('Yakin hapus konsultasi dari <?= e2(addslashes($row['name'])) ?>?')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
      <a href="<?= qstr(['page' => $page - 1]) ?>" class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">← Prev</a>
      <?php for ($p = max(1, $page-2); $p <= min($total_pages, $page+2); $p++): ?>
        <a href="<?= qstr(['page' => $p]) ?>" class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <a href="<?= qstr(['page' => $page + 1]) ?>" class="page-btn <?= $page >= $total_pages ? 'disabled' : '' ?>">Next →</a>
    </div>
    <p style="text-align:center;margin-top:.75rem;font-size:.75rem;color:var(--muted)">
      Menampilkan <?= count($rows) ?> dari <?= $total ?> data · Halaman <?= $page ?> / <?= $total_pages ?>
    </p>
    <?php endif; ?>

<?php admin_foot(); ?>
