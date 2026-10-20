<?php if (empty($promotions)) return; ?>
<?php // Promo banner carousel — auto-scrolling landscape banners ?>
<section class="uv-promo-band" data-promo-carousel>
  <div class="uv-container px-6 md:px-12">
    <div class="mb-10 text-center" data-reveal>
      <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Promo Spesial</span>
      <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Penawaran Terbatas</h2>
      <p class="mt-4 text-[#6B6459] max-w-md mx-auto">Nikmati promo eksklusif dari Uvora Clinic. Jangan sampai terlewat!</p>
    </div>
  </div>

  <!-- Carousel track -->
  <div class="uv-promo-track-wrap">
    <div class="uv-promo-track" data-promo-track>
      <?php foreach ($promotions as $pi => $p): ?>
        <a href="promo-detail.php?slug=<?= e($p['slug']) ?>" class="uv-promo-slide" data-reveal style="--d:<?= $pi * 0.08 ?>s">
          <div class="uv-promo-slide-img">
            <img src="<?= e($p['banner']) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
          </div>
          <div class="uv-promo-slide-overlay">
            <span class="uv-promo-badge"><?= e($p['category']) ?></span>
            <h3><?= e($p['title']) ?></h3>
            <p><?= e($p['subtitle']) ?></p>
            <span class="uv-promo-period"><?= icon('clock', 13) ?> <?= e($p['period']) ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Navigation arrows -->
    <button type="button" class="uv-promo-nav uv-promo-nav--prev" data-promo-prev aria-label="Promo sebelumnya">
      <?= icon('arrow-up-right', 18) ?>
    </button>
    <button type="button" class="uv-promo-nav uv-promo-nav--next" data-promo-next aria-label="Promo berikutnya">
      <?= icon('arrow-up-right', 18) ?>
    </button>
  </div>

  <!-- Dots -->
  <div class="uv-promo-dots" data-promo-dots>
    <?php foreach ($promotions as $pi => $p): ?>
      <button type="button" class="uv-promo-dot<?= $pi === 0 ? ' is-active' : '' ?>" data-promo-dot="<?= $pi ?>" aria-label="Promo <?= $pi + 1 ?>"></button>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-6" data-reveal>
    <a href="promo.php" class="inline-flex items-center gap-2 text-sm text-[#6B6459] transition-colors hover:text-[#ab8f2c]">
      Lihat Semua Promo <?= icon('arrow-up-right', 14) ?>
    </a>
  </div>
</section>

<script>
(function(){
  var root = document.querySelector('[data-promo-carousel]');
  if (!root) return;
  var track = root.querySelector('[data-promo-track]');
  var slides = track.querySelectorAll('.uv-promo-slide');
  var dots = root.querySelectorAll('[data-promo-dot]');
  var prev = root.querySelector('[data-promo-prev]');
  var next = root.querySelector('[data-promo-next]');
  var idx = 0, timer;

  function go(i) {
    idx = ((i % slides.length) + slides.length) % slides.length;
    var slide = slides[idx];
    // Center the active slide: its left edge minus the space needed to center it
    var trackRect = track.getBoundingClientRect();
    var slideRect = slide.getBoundingClientRect();
    var offset = track.scrollLeft + (slideRect.left - trackRect.left) - (trackRect.width - slideRect.width) / 2;
    track.scrollTo({ left: offset, behavior: 'smooth' });
    slides.forEach(function(s, n) { s.classList.toggle('is-active', n === idx); });
    dots.forEach(function(d, n) { d.classList.toggle('is-active', n === idx); });
  }
  function auto() { timer = setInterval(function() { go(idx + 1); }, 4500); }
  function stop() { clearInterval(timer); }

  prev.addEventListener('click', function() { stop(); go(idx - 1); auto(); });
  next.addEventListener('click', function() { stop(); go(idx + 1); auto(); });
  dots.forEach(function(d, n) {
    d.addEventListener('click', function() { stop(); go(n); auto(); });
  });

  root.addEventListener('mouseenter', stop);
  root.addEventListener('mouseleave', auto);
  go(0);
  auto();
})();
</script>
