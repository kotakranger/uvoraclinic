<?php
// Pembuka halaman (head + header) untuk halaman perawatan. Butuh: $page_title, $page_desc.
$asset_version = max(
    (int) @filemtime(__DIR__ . '/../assets/css/style.css'),
    (int) @filemtime(__DIR__ . '/../assets/css/perawatan.css'),
    (int) @filemtime(__DIR__ . '/../assets/js/main.js')
);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#FBF8F1">
  <meta name="description" content="<?= e($page_desc) ?>">
  <title><?= e($page_title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Manrope:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="assets/css/style.css?v=<?= e($asset_version) ?>">
  <link rel="stylesheet" href="assets/css/perawatan.css?v=<?= e($asset_version) ?>">
  <script>document.documentElement.classList.add('js');</script>
</head>
<body>
  <div class="bg-[#FBF8F1] text-[#353535] min-h-screen">
    <?php include __DIR__ . '/header.php'; ?>
