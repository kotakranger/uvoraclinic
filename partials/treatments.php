<section id="perawatan" class="uv-container px-6 py-24 md:px-12 md:py-32">
  <div class="mb-14 flex items-end justify-between">
    <div>
      <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Perawatan Unggulan</span>
      <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Solusi Medis Terkurasi</h2>
    </div>
    <a href="perawatan.php" class="hidden items-center gap-1 text-sm text-[#6B6459] hover:text-[#ab8f2c] md:flex">
      Semua perawatan <?= icon('arrow-up-right', 16) ?>
    </a>
  </div>
  <div class="pw-grid">
    <?php foreach ($featured_treatments as $i => $t): ?>
      <a href="perawatan-detail.php?slug=<?= e($t['slug']) ?>" class="pw-card" data-reveal style="--d:<?= $i * 0.1 ?>s">
        <div class="pw-card-img img-hover-wrap">
          <img src="<?= e($t['img']) ?>" alt="<?= e($t['name']) ?>" loading="lazy">
          <span class="pw-pill pw-pill-l"><?= e($t['cat_label']) ?></span>
          <span class="pw-pill pw-pill-r"><?= icon('clock', 12) ?> <?= e($t['dur']) ?></span>
        </div>
        <div class="pw-card-body">
          <h3 class="font-serif-display pw-card-name"><?= e($t['name']) ?></h3>
          <p class="pw-card-desc"><?= e($t['desc']) ?></p>
          <div class="pw-card-foot">
            <strong><?= e(treatment_price($t)) ?></strong>
            <span class="pw-more">Lihat detail <?= icon('arrow-up-right', 14) ?></span>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <div class="mt-10 text-center md:hidden">
    <a href="perawatan.php" class="inline-flex items-center gap-1 text-sm text-[#6B6459] hover:text-[#ab8f2c]">Semua perawatan <?= icon('arrow-up-right', 16) ?></a>
  </div>
</section>
