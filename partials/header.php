<div class="border-b border-[#E6DFD1] bg-white text-[#6B6459]">
  <div class="uv-container flex items-center justify-between px-6 py-2 text-xs md:px-12">
    <span class="flex items-center gap-2">
      <?= icon('clock', 13, 'text-[#ab8f2c]') ?>
      <?= e($site['opening_hours']) ?>
    </span>
    <a href="<?= e(whatsapp_link('Halo Uvora, saya butuh bantuan darurat.')) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-[#25D366]/40 bg-[#25D366]/10 px-5 py-2.5 text-sm font-medium text-[#166534] transition-colors hover:bg-[#25D366]/20 scale-90" data-testid="opt1-emergency-wa">
      <span class="h-2 w-2 rounded-full bg-[#25D366] animate-pulse"></span>
      Darurat: WhatsApp Kami
    </a>
  </div>
</div>

<header class="sticky top-0 z-30 border-b border-[#E6DFD1] bg-white/80 backdrop-blur-md">
  <div class="uv-container flex items-center justify-between px-6 py-4 md:px-12">
    <a href="#top" class="font-serif-display text-2xl tracking-tight">Uvora<span class="text-[#ab8f2c]">.</span></a>
    <nav class="hidden items-center gap-9 text-sm text-[#6B6459] lg:flex">
      <?php foreach ($nav_links as $link): ?>
        <a href="<?= e($link['href']) ?>" class="transition-colors hover:text-[#353535]" data-testid="opt1-nav-<?= e($link['label']) ?>"><?= e($link['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="flex items-center gap-3">
      <a href="#konsultasi" class="group relative overflow-hidden rounded-full bg-[#dfcdaf] px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-500 hover:bg-[#ab8f2c] hidden md:block" data-testid="opt1-book-btn">
        <span class="relative z-10">Book Consultation</span>
      </a>
      <button type="button" data-cart-open data-testid="cart-button" aria-label="Keranjang" class="relative flex h-11 w-11 items-center justify-center rounded-full border transition-colors border-[#E6DFD1] text-[#353535] hover:border-[#ab8f2c]">
        <?= icon('shopping-bag', 18) ?>
        <span data-cart-badge hidden class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#ab8f2c] px-1 text-[10px] font-semibold text-white"></span>
      </button>
      <button type="button" class="flex lg:hidden" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka menu" data-testid="opt1-menu">
        <span data-menu-icon="open" class="flex"><?= icon('menu') ?></span>
        <span data-menu-icon="close" class="flex" hidden><?= icon('x') ?></span>
      </button>
    </div>
  </div>

  <div id="mobile-menu" class="uv-collapse lg:hidden">
    <div class="overflow-hidden">
      <nav class="uv-container flex flex-col gap-1 border-t border-[#E6DFD1] px-6 py-4 md:px-12">
        <?php foreach ($nav_links as $link): ?>
          <a href="<?= e($link['href']) ?>" class="py-2 text-sm text-[#6B6459] transition-colors hover:text-[#353535]"><?= e($link['label']) ?></a>
        <?php endforeach; ?>
        <a href="#konsultasi" class="group relative overflow-hidden rounded-full bg-[#dfcdaf] px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-500 hover:bg-[#ab8f2c] mt-3 w-full text-center md:hidden">
          <span class="relative z-10">Book Consultation</span>
        </a>
      </nav>
    </div>
  </div>
</header>
