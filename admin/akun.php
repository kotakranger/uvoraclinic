<?php
/**
 * admin/akun.php — Ganti username & password admin.
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$pdo    = db();
$uid    = (int) $_SESSION['admin_uid'];
$force  = !empty($_SESSION['admin_must_change']);
$me     = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
$me->execute([$uid]);
$me = $me->fetch();
if (!$me) { admin_logout(); header('Location: login.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Sesi tidak valid, silakan muat ulang halaman.';
    } else {
        $current  = (string) ($_POST['current_password'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $new      = (string) ($_POST['new_password'] ?? '');
        $confirm  = (string) ($_POST['confirm_password'] ?? '');

        if (!password_verify($current, $me['password_hash'])) {
            $errors[] = 'Password saat ini salah.';
        }
        if (!preg_match('/^[A-Za-z0-9._-]{3,32}$/', $username)) {
            $errors[] = 'Username 3–32 karakter: huruf, angka, titik, strip, atau garis bawah.';
        }
        if ($new !== '') {
            if (mb_strlen($new) < ADMIN_MIN_PASS_LEN) $errors[] = 'Password baru minimal ' . ADMIN_MIN_PASS_LEN . ' karakter.';
            if ($new !== $confirm)                    $errors[] = 'Konfirmasi password tidak cocok.';
            if (strcasecmp($new, $username) === 0)     $errors[] = 'Password tidak boleh sama dengan username.';
        } elseif ($force) {
            $errors[] = 'Anda harus membuat password baru sebelum melanjutkan.';
        }

        if (!$errors) {
            try {
                $hash = $new !== '' ? password_hash($new, PASSWORD_BCRYPT) : $me['password_hash'];
                $pdo->prepare("UPDATE admin_users SET username = ?, password_hash = ?, must_change = 0 WHERE id = ?")
                    ->execute([$username, $hash, $uid]);
                session_regenerate_id(true);
                $_SESSION['admin_user']        = $username;
                $_SESSION['admin_must_change'] = false;
                flash_set('ok', 'Akun diperbarui.' . ($new !== '' ? ' Password baru sudah aktif.' : ''));
                header('Location: akun.php'); exit;
            } catch (PDOException $e) {
                $errors[] = ($e->errorInfo[1] ?? 0) == 1062 ? 'Username sudah dipakai.' : 'Gagal menyimpan akun.';
            }
        }
        $me['username'] = $username !== '' ? $username : $me['username'];
    }
}

$active_page = 'akun';
admin_head('Akun', $active_page);
flash_html();
?>
<div style="max-width:520px">
  <?php if ($force): ?>
    <div class="flash flash-err" style="background:#fffbeb;color:#92400e;border-color:#fde68a;">Demi keamanan, buat password baru sebelum memakai panel admin.</div>
  <?php endif; ?>
  <?php foreach ($errors as $err): ?>
    <div class="flash flash-err">✕ <?= he($err) ?></div>
  <?php endforeach; ?>

  <form method="POST" class="card card-body" autocomplete="off">
    <?= csrf_field() ?>
    <div class="form-group">
      <label class="lbl">Username</label>
      <input type="text" name="username" value="<?= he($me['username']) ?>" required autocomplete="username">
    </div>
    <div class="form-group">
      <label class="lbl">Password saat ini *</label>
      <input type="password" name="current_password" required autocomplete="current-password">
    </div>
    <div class="form-group">
      <label class="lbl">Password baru</label>
      <input type="password" name="new_password" minlength="<?= ADMIN_MIN_PASS_LEN ?>" autocomplete="new-password" <?= $force ? 'required' : '' ?>>
      <p class="hint">Minimal <?= ADMIN_MIN_PASS_LEN ?> karakter; kalimat sandi panjang lebih aman. Kosongkan jika hanya ganti username.</p>
    </div>
    <div class="form-group">
      <label class="lbl">Ulangi password baru</label>
      <input type="password" name="confirm_password" minlength="<?= ADMIN_MIN_PASS_LEN ?>" autocomplete="new-password">
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:.5rem">
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
  <p class="hint" style="margin-top:1rem;">Login dikunci 15 menit setelah <?= ADMIN_MAX_FAILS ?> percobaan gagal, dan otomatis keluar setelah 2 jam tidak aktif.</p>
</div>
<?php admin_foot(); ?>
