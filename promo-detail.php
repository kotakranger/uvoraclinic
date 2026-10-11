<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/catalog.php';
require __DIR__ . '/includes/icons.php';

// Find promo by slug
$promo = null;
$slug = $_GET['slug'] ?? '';
foreach ($promotions as $p) {
    if ($p['slug'] === $slug) { $promo = $p; break; }
}
if (!$promo) {
    http_response_code(404);
    header('Location: promo.php');
    exit;
}

$page_title = $promo['title'] . ' · Promo · ' . $site['name'];
$page_desc  = $promo['desc'];
$wa_promo   = whatsapp_link('Halo Uvora, saya tertarik dengan promo ' . $promo['title'] . '.');
include __DIR__ . '/partials/page-open.php';
?>

    <main>
      <!-- Breadcrumb & Title -->
      <section class="uv-container promo-d-head">
        <nav class="pw-crumb" aria-label="Breadcrumb">
          <a href="index.php">Beranda</a> / <a href="promo.php">Promo</a> / <span><?= e($promo['title']) ?></span>
        </nav>
        <h1 class="font-serif-display promo-d-title"><?= e($promo['title']) ?></h1>
        <p class="promo-d-period"><?= icon('clock', 14, 'text-[#ab8f2c]') ?> Periode: <?= e($promo['period']) ?></p>
      </section>

      <!-- Banner -->
      <section class="uv-container promo-d-banner-wrap">
        <div class="promo-d-banner" data-reveal>
          <img src="<?= e($promo['banner']) ?>" alt="<?= e($promo['title']) ?>">
        </div>
      </section>

      <!-- Detail content: description + T&C -->
      <section class="uv-container promo-d-content">
        <div class="promo-d-grid">
          <!-- Left: main info -->
          <div class="promo-d-info" data-reveal>
            <div class="promo-d-category-badges">
              <span class="promo-d-badge"><?= icon('sparkles', 14) ?> <?= e($promo['category']) ?></span>
            </div>

            <h2 class="font-serif-display promo-d-h2"><?= e($promo['title']) ?></h2>
            <p class="promo-d-desc"><?= e($promo['desc']) ?></p>

            <div class="promo-d-tnc">
              <h3>Syarat &amp; Ketentuan</h3>
              <ul>
                <?php foreach ($promo['terms'] as $term): ?>
                  <li><?= e($term) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>

            <div class="promo-d-period-box">
              <?= icon('clock', 16, 'text-[#ab8f2c]') ?>
              <div>
                <small>Periode promo</small>
                <strong><?= e($promo['period']) ?></strong>
              </div>
            </div>

            <!-- Share -->
            <div class="promo-d-share">
              <span>Bagikan promo ini</span>
              <?php
                $share_url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $share_text = $promo['title'] . ' — ' . $promo['subtitle'] . '. Lihat di Uvora Clinic! ' . $share_url;
              ?>
              <div class="promo-d-share-btns">
                <a href="https://wa.me/?text=<?= rawurlencode($share_text) ?>" target="_blank" rel="noopener noreferrer" class="promo-share-btn" aria-label="WhatsApp" title="Bagikan via WhatsApp"><?= icon('phone', 16) ?></a>
                <button type="button" class="promo-share-btn" data-copy-link aria-label="Salin link" title="Salin link"><?= icon('link', 16) ?></button>
              </div>
              <script>
              (function(){
                var b = document.querySelector('[data-copy-link]');
                if (!b) return;
                var icon = b.innerHTML;
                b.addEventListener('click', function(){
                  var url = location.href;
                  var done = function(){ b.textContent = '✓'; setTimeout(function(){ b.innerHTML = icon; }, 1500); };
                  if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(done, done);
                  } else {
                    var t = document.createElement('textarea'); t.value = url; document.body.appendChild(t); t.select();
                    try { document.execCommand('copy'); } catch (e) {}
                    document.body.removeChild(t); done();
                  }
                });
              })();
              </script>
            </div>
          </div>

          <!-- Right: CTA sidebar -->
          <aside class="promo-d-sidebar" data-reveal style="--d:.12s">
            <div class="promo-d-cta-card">
              <h3 class="font-serif-display">Tertarik?</h3>
              <p>Hubungi kami untuk menggunakan promo ini atau bertanya lebih lanjut.</p>
              <a href="<?= e($wa_promo) ?>" target="_blank" rel="noopener" class="pw-btn pw-btn-primary" style="width:100%;justify-content:center">
                <?= icon('phone', 16) ?> Klaim via WhatsApp
              </a>
              <a href="index.php#konsultasi" class="pw-btn pw-btn-ghost" style="width:100%;justify-content:center;margin-top:.75rem">
                Jadwalkan Konsultasi
              </a>
            </div>

            <!-- Other promos -->
            <?php
            $others = array_filter($promotions, fn($op) => $op['slug'] !== $promo['slug']);
            if ($others): ?>
              <div class="promo-d-others">
                <h4>Promo Lainnya</h4>
                <?php foreach ($others as $op): ?>
                  <a href="promo-detail.php?slug=<?= e($op['slug']) ?>" class="promo-d-other-card">
                    <img src="<?= e($op['banner']) ?>" alt="<?= e($op['title']) ?>" loading="lazy">
                    <div>
                      <strong><?= e($op['title']) ?></strong>
                      <span><?= e($op['period']) ?></span>
                    </div>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </aside>
        </div>
      </section>
    </main>

<?php include __DIR__ . '/partials/page-close.php'; ?>
