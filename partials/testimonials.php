<section id="testimoni" class="bg-[#f7f2ea] py-24 md:py-32">
  <div class="uv-container px-6 md:px-12">
    <div class="mb-14 flex flex-col items-center text-center">
      <span class="text-[#ab8f2c] tracking-widest text-2xl" aria-label="Rating 5 dari 5">★★★★★</span>
      <div class="font-serif-display mt-3 text-5xl"><?= e($review_summary['rating']) ?></div>
      <p class="mt-1 text-sm text-[#6B6459]">Berdasarkan <?= e($review_summary['count']) ?> ulasan Google</p>
    </div>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
      <?php foreach ($testimonials as $i => $t): ?>
        <div data-reveal style="--d:<?= $i * 0.1 ?>s">
          <div class="h-full rounded-3xl border border-[#E6DFD1] bg-white p-8">
            <span class="text-[#ab8f2c] tracking-widest" aria-label="Rating 5 dari 5">★★★★★</span>
            <p class="mt-4 text-sm leading-relaxed text-[#6B6459]">"<?= e($t['quote']) ?>"</p>
            <div class="mt-6 border-t border-[#E6DFD1] pt-4">
              <div class="font-medium"><?= e($t['name']) ?></div>
              <div class="text-xs text-[#9B9384]"><?= e($t['city']) ?></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
