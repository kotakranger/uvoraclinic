<?php $input_cls = 'rounded-xl border border-[#E6DFD1] bg-white px-4 py-3.5 text-sm outline-none transition-colors focus:border-[#ab8f2c]'; ?>
<section id="lokasi" class="uv-container grid grid-cols-1 gap-12 px-6 py-24 md:px-12 md:py-32 lg:grid-cols-2">
  <div data-reveal id="konsultasi">
    <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Konsultasi Cepat</span>
    <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Jadwalkan Kunjungan Anda</h2>
    <p class="mt-5 max-w-md text-[#6B6459]">Isi formulir dan tim kami akan menghubungi Anda dalam 1×24 jam untuk penjadwalan konsultasi.</p>
    <form class="mt-8 space-y-4" data-consult-form data-testid="opt1-consult-form">
      <?php /* Honeypot: hidden from real users via CSS, bots will fill it */ ?>
      <div style="position:absolute;left:-9999px;top:-9999px;opacity:0;pointer-events:none;" aria-hidden="true" tabindex="-1">
        <label for="hp-website">Website</label>
        <input type="text" id="hp-website" name="website" tabindex="-1" autocomplete="off">
      </div>
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <input name="name" data-testid="opt1-input-name" placeholder="Nama Lengkap" aria-label="Nama Lengkap" required class="<?= $input_cls ?>">
        <input name="phone" type="tel" data-testid="opt1-input-phone" placeholder="No. WhatsApp" aria-label="No. WhatsApp" required class="<?= $input_cls ?>">
      </div>
      <select name="treatment" required data-testid="opt1-input-treatment" aria-label="Perawatan yang diminati" class="w-full appearance-none <?= $input_cls ?> text-[#353535]" style="background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23ab8f2c%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><path d=%22m6 9 6 6 6-6%22/></svg>');background-repeat:no-repeat;background-position:right 1rem center;padding-right:2.5rem">
        <option value="" disabled selected>Perawatan yang diminati</option>
        <?php foreach ($treatment_categories as $cat): ?>
          <optgroup label="<?= e($cat['label']) ?>">
            <?php foreach ($cat['items'] as $it): ?>
              <option value="<?= e($it['name']) ?>"><?= e($it['name']) ?></option>
            <?php endforeach; ?>
          </optgroup>
        <?php endforeach; ?>
        <option value="Konsultasi dengan dokter">Belum tahu — konsultasi dengan dokter</option>
      </select>
      <textarea name="message" data-testid="opt1-input-message" placeholder="Ceritakan keluhan kulit Anda" aria-label="Keluhan kulit" rows="3" class="w-full <?= $input_cls ?>"></textarea>
      <!-- Cloudflare Turnstile — bot & DDoS protection -->
      <div class="cf-turnstile" data-sitekey="0x4AAAAAAFT0rr7xwWFH3z3U" data-theme="light" data-language="id"></div>
      <button type="submit" class="group relative overflow-hidden rounded-full bg-[#dfcdaf] px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-500 hover:bg-[#ab8f2c] w-full" data-testid="opt1-consult-submit">
        <span class="relative z-10" data-submit-label>Kirim Permintaan Konsultasi</span>
      </button>
      <p data-consult-error style="display:none;color:#b91c1c;font-size:.82rem;text-align:center;"></p>
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
