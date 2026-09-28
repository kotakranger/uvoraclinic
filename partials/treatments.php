<section id="perawatan" class="uv-container px-6 py-24 md:px-12 md:py-32">
  <div class="mb-14 flex items-end justify-between">
    <div>
      <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Perawatan Unggulan</span>
      <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Solusi Medis Terkurasi</h2>
    </div>
    <a href="#perawatan" class="hidden items-center gap-1 text-sm text-[#6B6459] hover:text-[#ab8f2c] md:flex">
      Semua perawatan <?= icon('arrow-up-right', 16) ?>
    </a>
  </div>
  <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
    <?php foreach ($treatments as $i => $t): ?>
      <div data-reveal style="--d:<?= $i * 0.1 ?>s">
        <div class="group overflow-hidden rounded-3xl border border-[#E6DFD1] bg-white shadow-[0_8px_32px_rgba(0,0,0,0.04)]">
          <div class="img-hover-wrap aspect-[4/3]">
            <img alt="<?= e($t['name']) ?>" class="h-full w-full object-cover" src="<?= e($t['img']) ?>" loading="lazy">
          </div>
          <div class="p-7">
            <span class="text-[10px] uppercase tracking-[0.2em] text-[#ab8f2c]"><?= e($t['tag']) ?></span>
            <h3 class="font-serif-display mt-2 text-2xl"><?= e($t['name']) ?></h3>
            <p class="mt-3 text-sm leading-relaxed text-[#6B6459]"><?= e($t['desc']) ?></p>
            <div class="mt-5 flex items-center justify-between border-t border-[#E6DFD1] pt-4 text-xs text-[#9B9384]">
              <span><?= e($t['price']) ?></span>
              <span><?= e($t['duration']) ?></span>
            </div>
            <a href="<?= e(whatsapp_link('Halo Uvora, saya ingin bertanya tentang perawatan ' . $t['name'] . '.')) ?>" target="_blank" rel="noopener" class="block text-center rounded-full border border-[#E6DFD1] bg-transparent px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-300 hover:border-[#ab8f2c] hover:text-[#ab8f2c] mt-5 w-full" data-testid="opt1-inquiry-<?= $i ?>">Tanya Perawatan</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
