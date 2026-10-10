<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/catalog.php';
require __DIR__ . '/includes/icons.php';

$page_title = 'Promo · ' . $site['name'];
$page_desc  = 'Promo dan penawaran terbatas dari Uvora Clinic. Nikmati diskon, paket hemat, dan program referral eksklusif.';
include __DIR__ . '/partials/page-open.php';
?>

    <main>
      <!-- Hero -->
      <section class="uv-container pw-hero" style="text-align:center;display:block;padding-top:3.5rem;padding-bottom:2rem">
        <div data-reveal>
          <span class="pw-eyebrow">Penawaran Spesial</span>
          <h1 class="font-serif-display pw-title" style="margin:1rem auto 0;max-width:700px">Promo Uvora Clinic</h1>
          <p class="pw-sub" style="margin:1.25rem auto 0;text-align:center">Jangan lewatkan berbagai penawaran menarik dari kami. Klik banner untuk melihat syarat dan ketentuan.</p>
        </div>
      </section>

      <!-- Promo listing -->
      <section class="uv-container" style="padding:0 1.5rem 5rem">
        <div class="promo-grid">
          <?php foreach ($promotions as $pi => $p): ?>
            <a href="promo-detail.php?slug=<?= e($p['slug']) ?>" class="promo-card" data-reveal style="--d:<?= $pi * 0.08 ?>s">
              <div class="promo-card-banner">
                <img src="<?= e($p['banner']) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                <span class="promo-card-badge"><?= e($p['category']) ?></span>
              </div>
              <div class="promo-card-body">
                <h2 class="font-serif-display promo-card-title"><?= e($p['title']) ?></h2>
                <p class="promo-card-sub"><?= e($p['subtitle']) ?></p>
                <div class="promo-card-meta">
                  <span class="promo-card-period"><?= icon('clock', 13, 'text-[#ab8f2c]') ?> <?= e($p['period']) ?></span>
                  <span class="pw-more">Lihat detail <?= icon('arrow-up-right', 14) ?></span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- CTA -->
      <section class="pw-ctaband-wrap">
        <div class="uv-container">
          <div class="pw-ctaband" data-reveal>
            <div>
              <span class="pw-eyebrow">Ada Pertanyaan?</span>
              <h2 class="font-serif-display pw-h2">Tanya via WhatsApp</h2>
              <p>Hubungi kami untuk informasi lebih lanjut tentang promo yang sedang berjalan.</p>
            </div>
            <a href="<?= e(whatsapp_link('Halo Uvora, saya ingin bertanya tentang promo yang sedang berjalan.')) ?>" target="_blank" rel="noopener" class="pw-btn pw-btn-primary" style="white-space:nowrap">Chat Sekarang</a>
          </div>
        </div>
      </section>
    </main>

<?php include __DIR__ . '/partials/page-close.php'; ?>
