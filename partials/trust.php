<section class="border-y border-[#E6DFD1] bg-white">
  <div class="uv-container grid grid-cols-2 gap-px px-6 md:grid-cols-4 md:px-12">
    <?php foreach ($trust_points as $i => $point): ?>
      <div data-reveal style="--d:<?= $i * 0.08 ?>s">
        <div class="px-3 py-10 md:px-6">
          <?= icon($point['icon'], 26, 'text-[#ab8f2c]') ?>
          <h3 class="mt-4 text-base font-medium"><?= e($point['title']) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-[#9B9384]"><?= e($point['text']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
