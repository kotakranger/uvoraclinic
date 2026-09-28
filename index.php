<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/icons.php';

// Cache-busting: berubah otomatis setiap kali CSS/JS di-upload ulang.
$asset_version = max(
    (int) @filemtime(__DIR__ . '/assets/css/style.css'),
    (int) @filemtime(__DIR__ . '/assets/js/main.js')
);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#FBF8F1">
  <meta name="description" content="<?= e($site['description']) ?>">
  <title><?= e($site['title']) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Manrope:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="assets/css/style.css?v=<?= e($asset_version) ?>">
  <script>document.documentElement.classList.add('js');</script>
</head>
<body>
  <div class="bg-[#FBF8F1] text-[#353535] min-h-screen">
    <?php
    include __DIR__ . '/partials/header.php';
    include __DIR__ . '/partials/hero.php';
    include __DIR__ . '/partials/trust.php';
    include __DIR__ . '/partials/treatments.php';
    include __DIR__ . '/partials/products.php';
    include __DIR__ . '/partials/results.php';
    include __DIR__ . '/partials/doctors.php';
    include __DIR__ . '/partials/testimonials.php';
    include __DIR__ . '/partials/contact.php';
    include __DIR__ . '/partials/footer.php';
    include __DIR__ . '/partials/cart-drawer.php';
    ?>
  </div>

  <script>
    window.UVORA = <?= json_encode([
        'whatsapp' => $site['whatsapp'],
        'products' => $products,
        'concerns' => $concerns,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?>;
  </script>
  <script src="assets/js/lenis.min.js?v=<?= e($asset_version) ?>" defer></script>
  <script src="assets/js/main.js?v=<?= e($asset_version) ?>" defer></script>
</body>
</html>
