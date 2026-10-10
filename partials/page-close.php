    <?php
    include __DIR__ . '/footer.php';
    include __DIR__ . '/cart-drawer.php';
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
