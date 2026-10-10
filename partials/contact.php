<?php $input_cls = 'rounded-xl border border-[#E6DFD1] bg-white px-4 py-3.5 text-sm outline-none transition-colors focus:border-[#ab8f2c]'; ?>
<section id="lokasi" class="uv-container grid grid-cols-1 gap-12 px-6 py-24 md:px-12 md:py-32 lg:grid-cols-2">
  <div data-reveal id="konsultasi">
    <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Konsultasi Cepat</span>
    <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Jadwalkan Kunjungan Anda</h2>
    <p class="mt-5 max-w-md text-[#6B6459]">Isi formulir dan tim kami akan menghubungi Anda dalam 1×24 jam untuk penjadwalan konsultasi.</p>
    <form class="mt-8 space-y-4" data-consult-form data-testid="opt1-consult-form">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <input name="name" data-testid="opt1-input-name" placeholder="Nama Lengkap" aria-label="Nama Lengkap" required class="<?= $input_cls ?>">
        <input name="phone" type="tel" data-testid="opt1-input-phone" placeholder="No. WhatsApp" aria-label="No. WhatsApp" required class="<?= $input_cls ?>">
      </div>
      <input name="treatment" data-testid="opt1-input-treatment" placeholder="Perawatan yang diminati" aria-label="Perawatan yang diminati" class="w-full <?= $input_cls ?>">
      <textarea name="message" data-testid="opt1-input-message" placeholder="Ceritakan keluhan kulit Anda" aria-label="Keluhan kulit" rows="3" class="w-full <?= $input_cls ?>"></textarea>
      <button type="submit" class="group relative overflow-hidden rounded-full bg-[#dfcdaf] px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-500 hover:bg-[#ab8f2c] w-full" data-testid="opt1-consult-submit">
        <span class="relative z-10" data-submit-label>Kirim Permintaan Konsultasi</span>
      </button>
    </form>
  </div>
  <div data-reveal style="--d:.1s">
    <div class="overflow-hidden rounded-3xl border border-[#E6DFD1] bg-white">
      <div style="position:relative;aspect-ratio:4/3;overflow:hidden;isolation:isolate">
        <iframe title="Peta lokasi Uvora Clinic" src="<?= e($site['maps_embed']) ?>" style="position:absolute;inset:0;width:100%;height:100%;border:0;z-index:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        <a href="<?= e($site['maps_url']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-white/90 px-5 py-2.5 text-sm shadow-lg transition-colors hover:bg-white hover:text-[#ab8f2c]" style="position:absolute;left:1rem;top:1rem;z-index:10;pointer-events:auto">
          <?= icon('map-pin', 16, 'text-[#ab8f2c]') ?> Buka di Google Maps
        </a>
      </div>
      <div class="space-y-3 p-8 text-sm text-[#6B6459]">
        <div class="flex items-start gap-3"><?= icon('map-pin', 18, 'mt-0.5 text-[#ab8f2c]') ?><?= e($site['address']) ?></div>
        <div class="flex items-center gap-3"><?= icon('clock', 18, 'text-[#ab8f2c]') ?><?= e($site['opening_hours']) ?></div>
        <div class="flex items-center gap-3"><?= icon('phone', 18, 'text-[#ab8f2c]') ?><a href="tel:+<?= e($site['whatsapp']) ?>" class="hover:text-[#ab8f2c]"><?= e($site['phone']) ?></a></div>
      </div>
    </div>
  </div>
</section>
