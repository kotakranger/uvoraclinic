<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/data.php';

// Override treatment categories from DB (falls back to config.php when empty)
$treatment_categories = load_treatment_categories($treatment_categories);
$doctors = load_doctors($doctors);

require __DIR__ . '/includes/catalog.php';
require __DIR__ . '/includes/icons.php';

$t = find_treatment($_GET['slug'] ?? '');
if (!$t) {
    http_response_code(404);
    header('Location: perawatan.php');
    exit;
}
$type = $treatment_types[$t['type']];

// Perawatan terkait: kategori sama dulu, lalu sisanya, maksimal 3.
$related = array_values(array_filter($catalog, fn($c) => $c['slug'] !== $t['slug'] && $c['cat'] === $t['cat']));
if (count($related) < 3) {
    foreach ($catalog as $c) {
        if ($c['slug'] !== $t['slug'] && !in_array($c, $related, true) && $c['type'] === $t['type']) {
            $related[] = $c;
        }
    }
}
$related = array_slice($related, 0, 3);

$page_title = $t['name'] . ' · Perawatan · ' . $site['name'];
$page_desc  = $t['desc'];
$wa_book    = whatsapp_link('Halo Uvora, saya ingin booking perawatan ' . $t['name'] . '.');
$wa_ask     = whatsapp_link('Halo Uvora, saya ingin bertanya tentang perawatan ' . $t['name'] . '.');
include __DIR__ . '/partials/page-open.php';
?>

    <main>
      <!-- Detail utama -->
      <section class="uv-container pw-d-wrap">
        <nav class="pw-crumb" aria-label="Breadcrumb">
          <a href="index.php">Beranda</a> / <a href="perawatan.php">Perawatan</a> / <span><?= e($t['name']) ?></span>
        </nav>
        <div class="pw-d-hero">
          <div class="pw-gallery" data-gallery>
            <div class="pw-d-img" data-stage>
              <?php foreach ($t['gallery'] as $gi => $g): ?>
                <div class="pw-slide<?= $gi === 0 ? ' is-active' : '' ?>" data-slide="<?= $gi ?>">
                  <?php if ($g['kind'] === 'image'): ?>
                    <img src="<?= e($g['src']) ?>" alt="<?= e($t['name']) ?> — foto <?= $gi + 1 ?>"<?= $gi > 0 ? ' loading="lazy"' : '' ?>>
                  <?php elseif ($g['kind'] === 'video'): ?>
                    <video src="<?= e($g['src']) ?>" controls playsinline preload="metadata"></video>
                  <?php else: ?>
                    <iframe data-src="<?= e($g['src']) ?>" title="Video <?= e($t['name']) ?>" allow="accelerometer; autoplay; encrypted-media; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
              <span class="pw-pill pw-pill-l"><?= e($t['cat_label']) ?></span>
              <?php if (count($t['gallery']) > 1): ?>
                <button type="button" class="pw-nav pw-nav-prev" data-prev aria-label="Sebelumnya"><?= icon('arrow-up-right', 18) ?></button>
                <button type="button" class="pw-nav pw-nav-next" data-next aria-label="Berikutnya"><?= icon('arrow-up-right', 18) ?></button>
                <span class="pw-counter"><span data-current>1</span> / <?= count($t['gallery']) ?></span>
              <?php endif; ?>
            </div>
            <?php if (count($t['gallery']) > 1): ?>
              <div class="pw-thumbs" role="tablist">
                <?php foreach ($t['gallery'] as $gi => $g): ?>
                  <button type="button" class="pw-thumb<?= $gi === 0 ? ' is-active' : '' ?>" data-thumb="<?= $gi ?>" aria-label="Tampilkan <?= $g['kind'] === 'image' ? 'foto' : 'video' ?> <?= $gi + 1 ?>">
                    <?php if ($g['kind'] === 'video'): ?>
                      <video src="<?= e($g['src']) ?>#t=0.5" muted preload="metadata" playsinline></video>
                    <?php else: ?>
                      <img src="<?= e($g['thumb']) ?>" alt="" loading="lazy">
                    <?php endif; ?>
                    <?php if ($g['kind'] !== 'image'): ?><span class="pw-play"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg></span><?php endif; ?>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="pw-d-info">
            <span class="pw-eyebrow"><?= e($t['cat_label']) ?></span>
            <h1 class="font-serif-display pw-d-title"><?= e($t['name']) ?></h1>
            <p class="pw-d-desc"><?= e($t['desc']) ?></p>
            <div class="pw-d-meta">
              <div><small>Harga</small><strong><?= e(rupiah($t['price'])) ?></strong></div>
              <div><small>Durasi</small><strong><?= e($t['dur']) ?></strong></div>
            </div>
            <div class="pw-hero-btns">
              <a href="<?= e($wa_book) ?>" target="_blank" rel="noopener" class="pw-btn pw-btn-primary">Booking via WhatsApp</a>
              <a href="<?= e($wa_ask) ?>" target="_blank" rel="noopener" class="pw-btn pw-btn-ghost">Tanya Dokter</a>
            </div>
            <p class="pw-d-note"><?= e($treatment_note) ?></p>
          </div>
        </div>
      </section>

      <!-- Cara kerja -->
      <section class="pw-band pw-band-soft">
        <div class="uv-container pw-how">
          <div>
            <span class="pw-eyebrow">Cara Kerja</span>
            <h2 class="font-serif-display pw-h2"><?= e($t['how_title']) ?></h2>
            <p class="pw-p"><?= e($t['how_text']) ?></p>
          </div>
          <div class="pw-how-grid">
            <?php foreach ($t['features'] as $f): ?>
              <div class="pw-how-item"><?= icon('sparkles', 16, 'text-[#ab8f2c]') ?><span><?= e($f) ?></span></div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- Cocok untuk / catatan -->
      <section class="pw-band pw-band-tint">
        <div class="uv-container pw-two">
          <div>
            <span class="pw-eyebrow">Cocok Untuk</span>
            <h2 class="font-serif-display pw-h2">Kondisi yang Dapat Dibantu</h2>
            <ul class="pw-list">
              <?php foreach ($t['suitable'] as $s): ?><li><?= icon('badge-check', 18, 'text-[#ab8f2c]') ?><?= e($s) ?></li><?php endforeach; ?>
            </ul>
          </div>
          <div>
            <span class="pw-eyebrow">Perlu Diketahui</span>
            <h2 class="font-serif-display pw-h2">Catatan Perawatan</h2>
            <ul class="pw-list">
              <?php foreach ($t['notes'] as $n): ?><li><?= icon('shield-check', 18, 'text-[#ab8f2c]') ?><?= e($n) ?></li><?php endforeach; ?>
            </ul>
          </div>
        </div>
      </section>

      <!-- Alur -->
      <section class="uv-container pw-section">
        <div class="pw-center">
          <span class="pw-eyebrow">Proses</span>
          <h2 class="font-serif-display pw-h2">Alur Perawatan</h2>
          <p class="pw-p">Tahapan umum yang akan Anda lalui. Detail dapat disesuaikan oleh dokter.</p>
        </div>
        <div class="pw-steps">
          <?php foreach ($t['steps'] as $si => $step): ?>
            <div class="pw-step" data-reveal style="--d:<?= $si * 0.07 ?>s">
              <span class="pw-step-no"><?= sprintf('%02d', $si + 1) ?></span>
              <h3><?= e($step[0]) ?></h3>
              <p><?= e($step[1]) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Dokter -->
      <?php if ($doctors): ?>
      <section class="uv-container pw-section">
        <div class="pw-center">
          <span class="pw-eyebrow">Tim Medis</span>
          <h2 class="font-serif-display pw-h2">Dokter yang Menangani</h2>
        </div>
        <div class="pw-docs">
          <?php foreach ($doctors as $d): ?>
            <div class="pw-doc" data-reveal>
              <div class="pw-doc-img img-hover-wrap"><?php if (!empty($d['img'])): ?><img src="<?= e($d['img']) ?>" alt="<?= e($d['name']) ?>" loading="lazy"><?php endif; ?></div>
              <strong><?= e($d['name']) ?></strong>
              <span><?= e($d['role']) ?></span>
              <?php if (!empty($d['schedule'])): ?>
                <div style="display:flex;align-items:center;gap:.4rem;margin-top:.6rem;font-size:.72rem;color:var(--uv-ink-2)">
                  <?= icon('clock', 13, 'text-[#ab8f2c]') ?> <?= e($d['schedule']) ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($d['expertise'])): ?>
                <ul style="display:flex;flex-direction:column;gap:.25rem;margin-top:.6rem;text-align:left">
                  <?php foreach ($d['expertise'] as $exp): ?>
                    <li style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;color:var(--uv-ink-2)">
                      <?= icon('badge-check', 12, 'text-[#ab8f2c]') ?> <?= e($exp) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- FAQ -->
      <section class="pw-band pw-band-soft">
        <div class="uv-container pw-faq">
          <div>
            <span class="pw-eyebrow">FAQ</span>
            <h2 class="font-serif-display pw-h2">Pertanyaan yang Sering Diajukan</h2>
            <p class="pw-p">Tidak menemukan jawabannya? Tanyakan langsung kepada kami.</p>
            <a href="<?= e($wa_ask) ?>" target="_blank" rel="noopener" class="pw-btn pw-btn-primary">Tanya via WhatsApp</a>
          </div>
          <div class="pw-acc">
            <?php
            $faqs = array_merge($type['faq'], [['Bagaimana cara booking?', 'Klik tombol Booking via WhatsApp, tim kami akan membantu menjadwalkan konsultasi dan perawatan Anda.']]);
            foreach ($faqs as $fi => $faq): ?>
              <details class="pw-q"<?= $fi === 0 ? ' open' : '' ?>>
                <summary><?= e($faq[0]) ?><span><?= icon('plus', 16) ?></span></summary>
                <p><?= e($faq[1]) ?></p>
              </details>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- Terkait -->
      <section class="uv-container pw-section">
        <div class="pw-cat-head">
          <div>
            <span class="pw-eyebrow">Rekomendasi</span>
            <h2 class="font-serif-display pw-h2">Perawatan Lain yang Mungkin Anda Suka</h2>
          </div>
          <a href="perawatan.php" class="pw-all">Semua perawatan <?= icon('arrow-up-right', 16) ?></a>
        </div>
        <div class="pw-grid">
          <?php foreach ($related as $r): ?>
            <a href="perawatan-detail.php?slug=<?= e($r['slug']) ?>" class="pw-card">
              <div class="pw-card-img img-hover-wrap">
                <img src="<?= e($r['img']) ?>" alt="<?= e($r['name']) ?>" loading="lazy">
                <span class="pw-pill pw-pill-l"><?= e($r['cat_label']) ?></span>
                <span class="pw-pill pw-pill-r"><?= icon('clock', 12) ?> <?= e($r['dur']) ?></span>
              </div>
              <div class="pw-card-body">
                <h3 class="font-serif-display pw-card-name"><?= e($r['name']) ?></h3>
                <p class="pw-card-desc"><?= e($r['desc']) ?></p>
                <div class="pw-card-foot">
                  <strong><?= e(treatment_price($r)) ?></strong>
                  <span class="pw-more">Lihat detail <?= icon('arrow-up-right', 14) ?></span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    </main>

    <script>
      (function () {
        var root = document.querySelector('[data-gallery]');
        if (!root) return;
        var slides = root.querySelectorAll('.pw-slide');
        var thumbs = root.querySelectorAll('.pw-thumb');
        var counter = root.querySelector('[data-current]');
        var index = 0;

        function show(i) {
          index = (i + slides.length) % slides.length;
          slides.forEach(function (s, n) {
            var active = n === index;
            s.classList.toggle('is-active', active);
            var v = s.querySelector('video');
            if (v && !active) v.pause();
            var f = s.querySelector('iframe');
            if (f) { if (active) { if (!f.src) f.src = f.dataset.src; } else { f.removeAttribute('src'); } }
          });
          thumbs.forEach(function (t, n) { t.classList.toggle('is-active', n === index); });
          if (counter) counter.textContent = index + 1;
          var active = thumbs[index];
          var bar = active && active.parentNode;
          if (bar) bar.scrollTo({ left: active.offsetLeft - (bar.clientWidth - active.clientWidth) / 2, behavior: 'smooth' });
        }

        thumbs.forEach(function (t, n) {
          t.addEventListener('click', function () { show(n); });
          t.addEventListener('mouseenter', function () { if (window.matchMedia('(hover:hover)').matches) show(n); });
        });
        var prev = root.querySelector('[data-prev]'), next = root.querySelector('[data-next]');
        if (prev) prev.addEventListener('click', function () { show(index - 1); });
        if (next) next.addEventListener('click', function () { show(index + 1); });
        root.addEventListener('keydown', function (e) {
          if (e.key === 'ArrowLeft') show(index - 1);
          if (e.key === 'ArrowRight') show(index + 1);
        });
        root.tabIndex = 0;
        show(0);
      })();
    </script>

<?php include __DIR__ . '/partials/page-close.php'; ?>
