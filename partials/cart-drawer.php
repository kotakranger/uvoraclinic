<div class="uv-overlay fixed inset-0 z-40 bg-black/30 backdrop-blur-[2px]" data-cart-close></div>
<aside class="uv-drawer fixed right-0 top-0 z-50 flex h-full w-full max-w-md flex-col bg-[#FBF8F1] shadow-2xl" data-cart-drawer data-lenis-prevent role="dialog" aria-modal="true" aria-label="Keranjang belanja" inert>
  <div class="flex items-center justify-between border-b border-[#E6DFD1] px-6 py-5">
    <div>
      <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Keranjang</span>
      <h3 class="font-serif-display mt-1 text-2xl">Pesanan Anda</h3>
    </div>
    <button type="button" data-cart-close aria-label="Tutup keranjang" class="flex h-10 w-10 items-center justify-center rounded-full border border-[#E6DFD1] transition-colors hover:border-[#ab8f2c] hover:text-[#ab8f2c]">
      <?= icon('x', 16) ?>
    </button>
  </div>

  <div class="flex flex-1 flex-col items-center justify-center px-6 text-center" data-cart-empty>
    <?= icon('shopping-bag', 32, 'text-[#dfcdaf]') ?>
    <p class="mt-4 text-sm text-[#6B6459]">Keranjang Anda masih kosong.</p>
    <button type="button" data-cart-close class="mt-6 rounded-full border border-[#E6DFD1] bg-transparent px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-300 hover:border-[#ab8f2c] hover:text-[#ab8f2c]">Lihat Produk</button>
  </div>

  <ul class="flex-1 space-y-4 overflow-y-auto px-6 py-6" data-cart-list hidden></ul>

  <div class="border-t border-[#E6DFD1] bg-white px-6 py-6" data-cart-footer hidden>
    <div class="flex items-center justify-between">
      <span class="text-sm text-[#6B6459]">Subtotal</span>
      <span class="font-serif-display text-2xl" data-cart-total></span>
    </div>
    <button type="button" data-cart-checkout class="group relative mt-5 w-full overflow-hidden rounded-full bg-[#dfcdaf] px-8 py-3.5 text-sm font-medium text-[#353535] transition-colors duration-500 hover:bg-[#ab8f2c]">
      <span class="relative z-10">Checkout via WhatsApp</span>
    </button>
    <button type="button" data-cart-clear class="mt-3 w-full text-xs text-[#9B9384] hover:text-[#ab8f2c]">Kosongkan keranjang</button>
  </div>
</aside>

<template data-cart-item>
  <li class="flex gap-4 rounded-2xl border border-[#E6DFD1] bg-white p-3">
    <img alt="" class="h-20 w-20 rounded-xl object-cover" data-item-img>
    <div class="flex flex-1 flex-col">
      <span class="text-[10px] uppercase tracking-[0.2em] text-[#ab8f2c]" data-item-category></span>
      <span class="text-sm font-semibold leading-snug" data-item-name></span>
      <div class="mt-auto flex items-center justify-between pt-2">
        <div class="flex items-center gap-2">
          <button type="button" aria-label="Kurangi" data-item-dec class="flex h-7 w-7 items-center justify-center rounded-full border border-[#E6DFD1] hover:border-[#ab8f2c]"><?= icon('minus', 12) ?></button>
          <span class="w-5 text-center text-sm" data-item-qty></span>
          <button type="button" aria-label="Tambah" data-item-inc class="flex h-7 w-7 items-center justify-center rounded-full border border-[#E6DFD1] hover:border-[#ab8f2c]"><?= icon('plus', 12) ?></button>
        </div>
        <span class="font-serif-display text-lg" data-item-total></span>
      </div>
    </div>
  </li>
</template>
