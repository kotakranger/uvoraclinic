<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/catalog.php';
require __DIR__ . '/includes/icons.php';

$page_title = 'Katalog Perawatan · ' . $site['name'];
$page_desc  = 'Katalog perawatan Uvora Clinic: hydrafacial, peel, skin booster, Rejuran, Botox dan lainnya. Pilih sesuai fokus kulit Anda.';

$featured = find_treatment('salmon-dna-booster') ?: $catalog[0];
include __DIR__ . '/partials/page-open.php';
?>

    <main>
      <!-- Hero -->
      <section class="pw-hero uv-container">
        <div class="pw-hero-text">
          <span class="pw-eyebrow uv-fade-up">Perawatan</span>
          <h1 class="font-serif-display pw-title uv-fade-up" style="--d:.1s">Katalog Perawatan<br>Terkurasi Medis</h1>
          <p class="pw-sub uv-fade-up" style="--d:.2s">Setiap perawatan dirancang dan ditangani dokter berlisensi. Pilih berdasarkan kategori — atau konsultasikan dulu agar rekomendasinya tepat.</p>
          <span class="pw-free uv-fade-up" style="--d:.25s"><?= icon('badge-check', 16) ?> <?= e($consult_badge) ?></span>
          <div class="pw-hero-btns uv-fade-up" style="--d:.3s">
            <a href="<?= e(whatsapp_link('Halo Uvora, saya ingin konsultasi perawatan.')) ?>" target="_blank" rel="noopener" class="pw-btn pw-btn-primary">Konsultasi Gratis</a>
            <a href="#katalog" class="pw-btn pw-btn-ghost">Lihat Katalog</a>
          </div>
        </div>
        <a href="perawatan-detail.php?slug=<?= e($featured['slug']) ?>" class="pw-hero-card uv-fade-up" style="--d:.2s">
          <img src="<?= e($featured['img']) ?>" alt="<?= e($featured['name']) ?>">
          <div class="pw-hero-card-info">
            <small>Paling Dicari</small>
            <strong><?= e($featured['name']) ?></strong>
            <span><?= e(treatment_price($featured)) ?></span>
          </div>
        </a>
      </section>

      <!-- Trust -->
      <section class="pw-trust">
        <div class="uv-container pw-trust-grid">
          <?php foreach (array_slice($trust_points, 0, 3) as $tp): ?>
            <div class="pw-trust-item">
              <?= icon($tp['icon'], 22, 'text-[#ab8f2c]') ?>
              <div>
                <strong><?= e($tp['title']) ?></strong>
                <p><?= e($tp['text']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Katalog -->
      <section id="katalog" class="pw-catalog uv-container">
        <div class="pw-cat-head">
          <div>
            <span class="pw-eyebrow">Katalog</span>
            <h2 class="font-serif-display pw-h2">Pilih Berdasarkan Kategori</h2>
          </div>
          <p class="pw-cat-note"><?= e($treatment_note) ?></p>
        </div>

        <div class="pw-filters" data-pw-filters>
          <div class="pw-chips" data-filter="cat">
            <button type="button" class="pw-chip is-active" data-value="all">Semua <em><?= count($catalog) ?></em></button>
            <?php foreach ($treatment_categories as $cid => $cat): ?>
              <button type="button" class="pw-chip" data-value="<?= e($cid) ?>"><?= e($cat['label']) ?> <em><?= count($cat['items']) ?></em></button>
            <?php endforeach; ?>
          </div>
        </div>

        <?php foreach ($price_tiers as $ti => $pt): ?>
          <?php $tier_items = array_filter($catalog, fn($c) => $c['tier'] === $ti); if (!$tier_items) continue; ?>
          <div class="pw-tier" data-tier-section>
            <div class="pw-tier-head">
              <span class="pw-tier-no"><?= e($pt['no']) ?></span>
              <div>
                <h3 class="font-serif-display pw-tier-title"><?= e($pt['name']) ?></h3>
                <p class="pw-tier-range"><?= e($pt['range']) ?></p>
              </div>
            </div>
            <div class="pw-grid">
              <?php foreach ($tier_items as $i => $t): ?>
                <a href="perawatan-detail.php?slug=<?= e($t['slug']) ?>" class="pw-card" data-cat="<?= e($t['cat']) ?>" data-reveal style="--d:<?= ($i % 3) * 0.08 ?>s">
                  <div class="pw-card-img img-hover-wrap">
                    <img src="<?= e($t['img']) ?>" alt="<?= e($t['name']) ?>" loading="lazy">
                    <span class="pw-pill pw-pill-l"><?= e($t['cat_label']) ?></span>
                    <span class="pw-pill pw-pill-r"><?= icon('clock', 12) ?> <?= e($t['dur']) ?></span>
                  </div>
                  <div class="pw-card-body">
                    <h4 class="font-serif-display pw-card-name"><?= e($t['name']) ?></h4>
                    <p class="pw-card-desc"><?= e($t['desc']) ?></p>
                    <div class="pw-card-foot">
                      <strong><?= e(treatment_price($t)) ?></strong>
                      <span class="pw-more">Lihat detail <?= icon('arrow-up-right', 14) ?></span>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        <p class="pw-empty" data-pw-empty hidden>Tidak ada perawatan untuk filter ini.</p>
      </section>

      <!-- CTA -->
      <section class="uv-container pw-ctaband-wrap">
        <div class="pw-ctaband">
          <div>
            <h2 class="font-serif-display pw-h2">Belum yakin perawatan mana yang cocok?</h2>
            <p>Konsultasikan kondisi kulit Anda dengan dokter kami. <?= e($treatment_note) ?></p>
          </div>
          <a href="<?= e(whatsapp_link('Halo Uvora, saya ingin konsultasi perawatan.')) ?>" target="_blank" rel="noopener" class="pw-btn pw-btn-primary">Konsultasi via WhatsApp</a>
        </div>
      </section>
    </main>

    <script>
      (function () {
        var cards = document.querySelectorAll('.pw-card[data-cat]');
        var sections = document.querySelectorAll('[data-tier-section]');
        var empty = document.querySelector('[data-pw-empty]');
        var state = { cat: 'all' };
        document.querySelectorAll('[data-pw-filters] .pw-chips').forEach(function (group) {
          group.addEventListener('click', function (ev) {
            var btn = ev.target.closest('.pw-chip');
            if (!btn) return;
            group.querySelectorAll('.pw-chip').forEach(function (b) { b.classList.toggle('is-active', b === btn); });
            state[group.dataset.filter] = btn.dataset.value;
            var shown = 0;
            cards.forEach(function (c) {
              var ok = state.cat === 'all' || c.dataset.cat === state.cat;
              c.hidden = !ok;
              if (ok) { shown++; c.classList.add('is-visible'); }
            });
            sections.forEach(function (s) { s.hidden = !s.querySelector('.pw-card:not([hidden])'); });
            empty.hidden = shown !== 0;
          });
        });
      })();
    </script>

<?php include __DIR__ . '/partials/page-close.php'; ?>
