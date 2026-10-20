<?php if (empty($concerns)) return; ?>
<?php $first = $concerns[0]; ?>
<style>
  #hasil .ba-layout { display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center; max-width: 1120px; margin: 0 auto; }
  #hasil .ba-frame { aspect-ratio: 4 / 3; box-shadow: 0 24px 60px rgba(53,53,53,.12); }
  @media (min-width: 640px) { #hasil .ba-frame { aspect-ratio: 3 / 2; } }
  @media (min-width: 1024px) { #hasil .ba-layout { grid-template-columns: 1.65fr 1fr; gap: 4rem; } #hasil .ba-frame { aspect-ratio: 4 / 3; } }
  #hasil .ba-tag { font-size: 11px; padding: .4rem .9rem; letter-spacing: .14em; }
  #hasil .ba-frame img { image-rendering: auto; }
</style>
<section id="hasil" class="bg-white py-24 md:py-32">
  <div class="uv-container px-6 md:px-12">
    <div class="mb-10 text-center">
      <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Galeri Transformasi</span>
      <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Hasil Berbicara</h2>
    </div>
    <div class="mb-10 flex flex-wrap justify-center gap-3" data-concern-tabs>
      <?php foreach ($concerns as $i => $c): ?>
        <button type="button" data-concern="<?= e($c['id']) ?>" data-testid="concern-<?= e($c['id']) ?>" aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>" class="rounded-full px-6 py-2.5 text-sm transition-colors <?= $i === 0 ? 'bg-[#dfcdaf] text-[#353535]' : 'border border-[#E6DFD1] text-[#6B6459] hover:border-[#ab8f2c]' ?>"><?= e($c['label']) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="ba-layout">
      <div data-reveal>
        <div class="w-full" data-testid="before-after-slider">
          <div class="ba-frame relative w-full select-none overflow-hidden rounded-3xl border border-[#E6DFD1]" data-ba style="touch-action: pan-y">
            <img alt="Sesudah" class="absolute inset-0 h-full w-full object-cover" src="<?= e($first['after']) ?>" data-ba-after draggable="false" loading="lazy">
            <span class="absolute right-4 top-4 z-10 ba-tag rounded-full bg-white/90 uppercase text-[#353535]">Sesudah</span>
            <div class="absolute inset-0 overflow-hidden" data-ba-clip style="width: 50%">
              <img alt="Sebelum" class="absolute inset-0 h-full w-full object-cover" src="<?= e($first['before']) ?>" data-ba-before draggable="false" loading="lazy" style="width: 200%; max-width: none">
              <span class="absolute left-4 top-4 z-10 ba-tag rounded-full bg-black/60 uppercase text-white">Sebelum</span>
            </div>
            <div class="absolute top-0 z-20 h-full w-[2px] cursor-ew-resize bg-[#dfcdaf]" data-ba-handle data-testid="before-after-handle" style="left: 50%" role="slider" tabindex="0" aria-label="Geser untuk membandingkan sebelum dan sesudah" aria-valuemin="0" aria-valuemax="100" aria-valuenow="50">
              <span class="absolute top-1/2 left-1/2 flex h-11 w-11 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-[#dfcdaf] text-[#353535] shadow-lg">⟷</span>
            </div>
          </div>
          <p class="mt-3 text-xs italic text-[#9B9384]">*Hasil dapat berbeda pada setiap individu. Foto digunakan atas persetujuan pasien.</p>
        </div>
      </div>
      <div class="uv-swap" data-concern-detail>
        <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium" data-concern-label><?= e($first['label']) ?></span>
        <h3 class="font-serif-display mt-3 text-3xl" data-concern-treatment><?= e($first['treatment']) ?></h3>
        <p class="mt-4 text-[#6B6459]" data-concern-text><?= e($first['text']) ?></p>
        <div class="mt-6 space-y-3">
          <?php foreach ($concern_benefits as $b): ?>
            <div class="flex items-center gap-3 text-sm text-[#6B6459]">
              <?= icon('badge-check', 18, 'text-[#ab8f2c]') ?>
              <?= e($b) ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
