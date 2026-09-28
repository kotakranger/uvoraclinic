<section id="products" class="py-24 md:py-32 bg-[#f7f2ea]">
  <div class="uv-container px-6 md:px-12">
    <div class="mb-14 flex flex-col items-start justify-between gap-4 md:flex-row md:items-end">
      <div>
        <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Uvora Skincare</span>
        <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Rangkaian Produk Klinis</h2>
      </div>
      <p class="max-w-sm text-sm text-[#6B6459]">Diformulasikan oleh dokter kami untuk melanjutkan hasil perawatan di rumah.</p>
    </div>
    <div class="grid grid-cols-2 gap-5 md:grid-cols-4 md:gap-8">
      <?php foreach ($products as $i => $p): ?>
        <div data-reveal style="--d:<?= $i * 0.08 ?>s">
          <div class="group flex h-full flex-col overflow-hidden rounded-3xl border border-[#E6DFD1] bg-white" data-testid="product-<?= e($p['id']) ?>">
            <div class="img-hover-wrap aspect-square bg-[#f7f2ea]">
              <img alt="<?= e($p['name']) ?>" class="h-full w-full object-cover" src="<?= e($p['img']) ?>" loading="lazy">
            </div>
            <div class="flex flex-1 flex-col p-5">
              <span class="text-[10px] uppercase tracking-[0.2em] text-[#ab8f2c]"><?= e($p['category']) ?></span>
              <h3 class="mt-1.5 text-sm font-semibold leading-snug"><?= e($p['name']) ?></h3>
              <p class="mt-1 hidden text-xs text-[#9B9384] md:block"><?= e($p['desc']) ?></p>
              <div class="mt-auto pt-4">
                <div class="font-serif-display text-xl text-[#353535]"><?= e(rupiah($p['price'])) ?></div>
                <button type="button" data-add-to-cart="<?= e($p['id']) ?>" data-testid="add-to-cart-<?= e($p['id']) ?>" class="mt-3 flex w-full items-center justify-center gap-1.5 rounded-full bg-[#dfcdaf] py-2.5 text-xs font-semibold text-[#353535] transition-colors hover:bg-[#ab8f2c] hover:text-white">
                  <?= icon('shopping-bag', 14) ?>
                  Tambah
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
