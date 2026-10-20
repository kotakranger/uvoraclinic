<div class="border-b border-[#E6DFD1] bg-white text-[#6B6459]">
  <div class="uv-container flex items-center justify-between px-6 py-2 text-xs md:px-12">
    <span class="flex items-center gap-2">
      <?= icon('clock', 13, 'text-[#ab8f2c]') ?>
      <?= e($site['opening_hours']) ?>
    </span>
    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="inline-flex items-center gap-2 transition-colors hover:text-[#ab8f2c]" data-testid="opt1-top-wa">
      <?= icon('phone', 13, 'text-[#ab8f2c]') ?>
      WhatsApp: 0812-3456-7890
    </a>
  </div>
</div>

<?php
$_nav_need = ['promo.php' => 'promos', 'index.php#dokter' => 'doctors', 'index.php#hasil' => 'results', 'index.php#testimoni' => 'testimonials'];
$nav_links = array_values(array_filter($nav_links, function ($l) use ($_nav_need) {
    return !isset($_nav_need[$l['href']]) || site_has($_nav_need[$l['href']]);
}));
?>
<header class="sticky top-0 z-30 border-b border-[#E6DFD1] bg-white/80 backdrop-blur-md">
  <div class="uv-container flex items-center justify-between px-6 py-4 md:px-12">
    <a href="index.php" class="font-serif-display text-2xl tracking-tight">Uvora<span class="text-[#ab8f2c]">.</span></a>
    <nav class="hidden items-center gap-9 text-sm text-[#6B6459] lg:flex">
      <?php foreach ($nav_links as $link): ?>
        <a href="<?= e($link['href']) ?>" class="transition-colors hover:text-[#353535]" data-testid="opt1-nav-<?= e($link['label']) ?>"><?= e($link['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="flex items-center gap-3">
      <a href="index.php#konsultasi" class="group relative overflow-hidden rounded-full bg-[#dfcdaf] px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-500 hover:bg-[#ab8f2c] hidden md:block" data-testid="opt1-book-btn">
        <span class="relative z-10">Book Consultation</span>
      </a>
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
        <a href="index.php#konsultasi" class="group relative overflow-hidden rounded-full bg-[#dfcdaf] px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-500 hover:bg-[#ab8f2c] mt-3 w-full text-center md:hidden">
          <span class="relative z-10">Book Consultation</span>
        </a>
      </nav>
    </div>
  </div>
</header>
