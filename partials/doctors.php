<section id="dokter" class="uv-container px-6 py-24 md:px-12 md:py-32">
  <div class="mb-14 text-center">
    <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Tim Medis Kami</span>
    <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Ditangani Ahlinya</h2>
  </div>
  <div class="mx-auto grid max-w-4xl grid-cols-1 gap-10 md:grid-cols-2">
    <?php foreach ($doctors as $i => $d): ?>
      <div data-reveal style="--d:<?= $i * 0.1 ?>s">
        <div class="uv-doc-card overflow-hidden rounded-3xl border border-[#E6DFD1] bg-white" style="box-shadow:0 14px 40px rgba(0,0,0,.06)">
          <!-- Photo -->
          <div class="img-hover-wrap relative aspect-[3/4] overflow-hidden">
            <img alt="<?= e($d['name']) ?>" class="h-full w-full object-cover" src="<?= e($d['img']) ?>" loading="lazy">
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/30 to-transparent"></div>
          </div>
          <!-- Info -->
          <div class="p-5" style="padding-top:1.25rem">
            <h3 class="font-serif-display text-xl"><?= e($d['name']) ?></h3>
            <p class="text-sm text-[#ab8f2c]"><?= e($d['role']) ?></p>
            <?php if (!empty($d['schedule'])): ?>
              <div class="mt-3 flex items-center gap-2 text-xs text-[#6B6459]" style="padding:.55rem .85rem;background:var(--uv-bg-2);border-radius:.75rem">
                <?= icon('clock', 14, 'text-[#ab8f2c]') ?>
                <span><?= e($d['schedule']) ?></span>
              </div>
            <?php endif; ?>
            <?php if (!empty($d['expertise'])): ?>
              <div class="mt-3">
                <p class="text-[10px] uppercase tracking-[0.2em] text-[#9B9384] font-medium" style="margin-bottom:.5rem">Expertise</p>
                <ul style="display:flex;flex-direction:column;gap:.35rem">
                  <?php foreach ($d['expertise'] as $exp): ?>
                    <li class="flex items-start gap-2 text-xs text-[#6B6459]" style="line-height:1.5">
                      <?= icon('badge-check', 13, 'text-[#ab8f2c]') ?> <?= e($exp) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
