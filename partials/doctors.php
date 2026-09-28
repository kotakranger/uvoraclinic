<section id="dokter" class="uv-container px-6 py-24 md:px-12 md:py-32">
  <div class="mb-14 text-center">
    <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Tim Medis Kami</span>
    <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Ditangani Ahlinya</h2>
  </div>
  <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
    <?php foreach ($doctors as $i => $d): ?>
      <div data-reveal style="--d:<?= $i * 0.1 ?>s">
        <div class="text-center">
          <div class="img-hover-wrap relative mx-auto aspect-[3/4] overflow-hidden rounded-3xl border border-[#E6DFD1]">
            <img alt="<?= e($d['name']) ?>" class="h-full w-full object-cover" src="<?= e($d['img']) ?>" loading="lazy">
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/30 to-transparent"></div>
          </div>
          <h3 class="font-serif-display mt-5 text-xl"><?= e($d['name']) ?></h3>
          <p class="text-sm text-[#ab8f2c]"><?= e($d['role']) ?></p>
          <p class="mt-1 text-xs text-[#9B9384]">Pengalaman <?= e($d['exp']) ?></p>
          <div class="mt-3 flex justify-center gap-2">
            <?php foreach ($d['tags'] as $tag): ?>
              <span class="rounded-full border border-[#E6DFD1] px-3 py-1 text-[10px] uppercase tracking-wider text-[#6B6459]"><?= e($tag) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
