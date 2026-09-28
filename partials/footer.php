<footer class="border-t border-[#E6DFD1] bg-white">
  <div class="uv-container grid grid-cols-1 gap-10 px-6 py-16 md:grid-cols-4 md:px-12">
    <div>
      <div class="font-serif-display text-2xl">Uvora<span class="text-[#ab8f2c]">.</span></div>
      <p class="mt-4 max-w-xs text-sm text-[#9B9384]">Klinik estetika medis premium untuk kulit sehat dan bercahaya secara alami.</p>
    </div>
    <div>
      <h4 class="text-xs uppercase tracking-[0.2em] text-[#9B9384]">Perawatan</h4>
      <ul class="mt-4 space-y-2 text-sm text-[#6B6459]">
        <?php foreach ($footer_treatments as $t): ?>
          <li><a href="#perawatan" class="hover:text-[#ab8f2c]"><?= e($t) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h4 class="text-xs uppercase tracking-[0.2em] text-[#9B9384]">Jam Operasional</h4>
      <ul class="mt-4 space-y-2 text-sm text-[#6B6459]">
        <?php foreach ($footer_hours as $h): ?>
          <li><?= e($h) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h4 class="text-xs uppercase tracking-[0.2em] text-[#9B9384]">Ikuti Kami</h4>
      <div class="mt-4 flex gap-3">
        <?php foreach ($socials as $s): ?>
          <a href="<?= e($s['href']) ?>" aria-label="<?= e($s['label']) ?>" class="flex h-10 w-10 items-center justify-center rounded-full border border-[#E6DFD1] transition-colors hover:border-[#ab8f2c] hover:text-[#ab8f2c]"><?= icon($s['icon'], 16) ?></a>
        <?php endforeach; ?>
      </div>
      <a href="<?= e(whatsapp_link('Halo Uvora, saya ingin bertanya.')) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-[#25D366]/40 bg-[#25D366]/10 px-5 py-2.5 text-sm font-medium text-[#166534] transition-colors hover:bg-[#25D366]/20 mt-4" data-testid="footer-wa">
        <span class="h-2 w-2 rounded-full bg-[#25D366] animate-pulse"></span>
        Chat WhatsApp
      </a>
    </div>
  </div>
  <div class="border-t border-[#E6DFD1] py-6 text-center text-xs text-[#9B9384]">© <?= date('Y') ?> Uvora Clinic. Seluruh hak cipta dilindungi.</div>
</footer>
