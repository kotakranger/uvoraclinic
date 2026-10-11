<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Sesi tidak valid, silakan muat ulang halaman.';
    } else {
        $user = trim($_POST['username'] ?? '');
        $pass = $_POST['password'] ?? '';
        if (admin_login($user, $pass)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

// Generate CSRF token for the form
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login · Uvora Clinic</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Cormorant+Garamond:wght@600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --gold:    #ab8f2c;
      --gold-lt: #dfcdaf;
      --cream:   #faf8f4;
      --dark:    #1a1714;
      --muted:   #6b6459;
      --border:  #e6dfd1;
      --radius:  16px;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--cream);
      min-height: 100svh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }

    .card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 380px;
      box-shadow: 0 4px 24px rgba(0,0,0,.06);
    }

    .logo {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.6rem;
      color: var(--dark);
      letter-spacing: -.01em;
      text-align: center;
      margin-bottom: .25rem;
    }

    .sub {
      text-align: center;
      font-size: .78rem;
      color: var(--muted);
      margin-bottom: 2rem;
      letter-spacing: .06em;
      text-transform: uppercase;
    }

    label {
      display: block;
      font-size: .78rem;
      font-weight: 500;
      color: var(--dark);
      margin-bottom: .4rem;
    }

    input {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: .75rem 1rem;
      font-size: .9rem;
      font-family: inherit;
      outline: none;
      transition: border-color .2s;
      background: var(--cream);
      color: var(--dark);
    }

    input:focus { border-color: var(--gold); background: #fff; }

    .field { margin-bottom: 1rem; }

    .error-box {
      background: #fff1f1;
      border: 1px solid #fca5a5;
      border-radius: 10px;
      padding: .7rem 1rem;
      font-size: .82rem;
      color: #b91c1c;
      margin-bottom: 1rem;
    }

    button[type="submit"] {
      width: 100%;
      background: var(--gold);
      color: #fff;
      border: none;
      border-radius: 999px;
      padding: .85rem;
      font-size: .9rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: opacity .2s, transform .1s;
      margin-top: .5rem;
    }

    button[type="submit"]:hover  { opacity: .88; }
    button[type="submit"]:active { transform: scale(.98); }

    .back {
      text-align: center;
      margin-top: 1.25rem;
      font-size: .8rem;
      color: var(--muted);
    }

    .back a { color: var(--gold); text-decoration: none; }
    .back a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="card">
    <p class="logo">Uvora Clinic</p>
    <p class="sub">Admin Panel</p>

    <?php if ($error): ?>
      <div class="error-box"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

      <div class="field">
        <label for="admin-user">Username</label>
        <input id="admin-user" type="text" name="username" required autofocus autocomplete="username"
               value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      </div>

      <div class="field">
        <label for="admin-pass">Password</label>
        <input id="admin-pass" type="password" name="password" required autocomplete="current-password">
      </div>

      <button type="submit">Masuk</button>
    </form>

    <p class="back"><a href="../index.php">← Kembali ke website</a></p>
  </div>
</body>
</html>
