(function () {
  "use strict";

  var DATA = window.UVORA || { products: [], concerns: [], whatsapp: "" };
  var $ = function (sel, root) { return (root || document).querySelector(sel); };
  var $$ = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function rupiah(n) { return "Rp " + n.toLocaleString("id-ID"); }
  function whatsappLink(text) {
    return "https://wa.me/" + DATA.whatsapp + (text ? "?text=" + encodeURIComponent(text) : "");
  }
  function openWhatsApp(text) { window.open(whatsappLink(text), "_blank", "noopener"); }

  // ---------------------------------------------------------------- Smooth scroll
  var lenis = null;
  if (window.Lenis && !reduceMotion) {
    lenis = new window.Lenis({ autoRaf: true, duration: 1.2 });
  }

  function scrollToTarget(target) {
    if (lenis) lenis.scrollTo(target, { offset: -80 });
    else if (target === 0) window.scrollTo({ top: 0, behavior: "smooth" });
    else target.scrollIntoView({ behavior: "smooth" });
  }

  document.addEventListener("click", function (e) {
    var link = e.target.closest('a[href^="#"]');
    if (!link) return;
    var id = link.getAttribute("href").slice(1);
    var target = id === "top" ? 0 : document.getElementById(id);
    if (target === null) return;
    e.preventDefault();
    closeMenu();
    scrollToTarget(target);
  });

  // ---------------------------------------------------------------- Hero parallax
  var hero = $("[data-hero]");
  var heroImg = hero && $(".uv-hero-img", hero);
  if (heroImg && !reduceMotion) {
    var ticking = false;
    var updateParallax = function () {
      var h = hero.offsetHeight;
      var progress = Math.min(1, Math.max(0, window.scrollY / h));
      heroImg.style.setProperty("--parallax", (progress * h * 0.25).toFixed(1) + "px");
      ticking = false;
    };
    window.addEventListener("scroll", function () {
      if (!ticking) { ticking = true; requestAnimationFrame(updateParallax); }
    }, { passive: true });
    updateParallax();
  }

  // ---------------------------------------------------------------- Reveal on scroll
  var reveals = $$("[data-reveal]");
  if ("IntersectionObserver" in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: "0px 0px -80px 0px" });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add("is-visible"); });
  }

  // ---------------------------------------------------------------- Mobile menu
  var menu = $("#mobile-menu");
  var menuToggle = $("[data-menu-toggle]");

  function setMenu(open) {
    if (!menu || !menuToggle) return;
    menu.classList.toggle("is-open", open);
    menuToggle.setAttribute("aria-expanded", String(open));
    menuToggle.setAttribute("aria-label", open ? "Tutup menu" : "Buka menu");
    $('[data-menu-icon="open"]', menuToggle).hidden = open;
    $('[data-menu-icon="close"]', menuToggle).hidden = !open;
  }
  function closeMenu() { setMenu(false); }
  if (menuToggle) {
    menuToggle.addEventListener("click", function () {
      setMenu(menuToggle.getAttribute("aria-expanded") !== "true");
    });
  }

  // ---------------------------------------------------------------- Before / after slider
  var ba = $("[data-ba]");
  var baPos = 50;

  function setSlider(pct) {
    baPos = Math.min(99, Math.max(1, pct));
    $("[data-ba-clip]", ba).style.width = baPos + "%";
    $("[data-ba-before]", ba).style.width = (100 / baPos) * 100 + "%";
    var handle = $("[data-ba-handle]", ba);
    handle.style.left = baPos + "%";
    handle.setAttribute("aria-valuenow", String(Math.round(baPos)));
  }

  if (ba) {
    var dragging = false;
    var fromEvent = function (e) {
      var rect = ba.getBoundingClientRect();
      setSlider(((e.clientX - rect.left) / rect.width) * 100);
    };
    ba.addEventListener("pointerdown", function (e) {
      dragging = true;
      if (ba.setPointerCapture) ba.setPointerCapture(e.pointerId);
      fromEvent(e);
    });
    ba.addEventListener("pointermove", function (e) { if (dragging) fromEvent(e); });
    ba.addEventListener("pointerup", function () { dragging = false; });
    ba.addEventListener("pointercancel", function () { dragging = false; });
    $("[data-ba-handle]", ba).addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") { e.preventDefault(); setSlider(baPos - 5); }
      if (e.key === "ArrowRight") { e.preventDefault(); setSlider(baPos + 5); }
    });
  }

  // ---------------------------------------------------------------- Concern tabs
  var TAB_ACTIVE = "bg-[#dfcdaf] text-[#353535]";
  var TAB_INACTIVE = "border border-[#E6DFD1] text-[#6B6459] hover:border-[#ab8f2c]";
  var detail = $("[data-concern-detail]");
  var swapTimer = null;

  $$("[data-concern]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var concern = DATA.concerns.find(function (c) { return c.id === btn.dataset.concern; });
      if (!concern) return;

      $$("[data-concern]").forEach(function (b) {
        var active = b === btn;
        b.setAttribute("aria-pressed", String(active));
        TAB_ACTIVE.split(" ").forEach(function (c) { b.classList.toggle(c, active); });
        TAB_INACTIVE.split(" ").forEach(function (c) { b.classList.toggle(c, !active); });
      });

      if (ba) {
        $("[data-ba-after]", ba).src = concern.after;
        $("[data-ba-before]", ba).src = concern.before;
        ba.classList.remove("uv-fade-up");
        void ba.offsetWidth; // restart animasi
        ba.classList.add("uv-fade-up");
      }

      clearTimeout(swapTimer);
      detail.classList.add("is-fading");
      swapTimer = setTimeout(function () {
        $("[data-concern-label]", detail).textContent = concern.label;
        $("[data-concern-treatment]", detail).textContent = concern.treatment;
        $("[data-concern-text]", detail).textContent = concern.text;
        detail.classList.remove("is-fading");
      }, reduceMotion ? 0 : 400);
    });
  });

  // ---------------------------------------------------------------- Cart
  var STORAGE_KEY = "uvora-cart";
  var CART_ACTIVE = "border-[#ab8f2c] text-[#ab8f2c]";
  var CART_INACTIVE = "border-[#E6DFD1] text-[#353535] hover:border-[#ab8f2c]";
  var cart = {};
  try { cart = JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; } catch (err) { cart = {}; }

  var cartBtn = $("[data-cart-open]");
  var badge = $("[data-cart-badge]");
  var drawer = $("[data-cart-drawer]");
  var list = $("[data-cart-list]");
  var itemTpl = $("template[data-cart-item]");
  var lastCount = 0;

  function cartLines() {
    return DATA.products
      .filter(function (p) { return cart[p.id] > 0; })
      .map(function (p) { return Object.assign({}, p, { qty: cart[p.id] }); });
  }

  function saveCart() {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(cart)); } catch (err) { /* storage tidak tersedia */ }
  }

  function setQty(id, qty) {
    if (qty <= 0) delete cart[id];
    else cart[id] = qty;
    saveCart();
    renderCart();
  }

  function renderCart() {
    var lines = cartLines();
    var count = lines.reduce(function (s, l) { return s + l.qty; }, 0);
    var total = lines.reduce(function (s, l) { return s + l.qty * l.price; }, 0);

    // Tombol & badge di header
    var active = count > 0;
    CART_ACTIVE.split(" ").forEach(function (c) { cartBtn.classList.toggle(c, active); });
    CART_INACTIVE.split(" ").forEach(function (c) { cartBtn.classList.toggle(c, !active); });
    cartBtn.setAttribute("aria-label", "Keranjang (" + count + " item)");
    badge.hidden = !active;
    badge.textContent = String(count);
    if (active && count !== lastCount && !reduceMotion) {
      badge.classList.remove("uv-pop");
      void badge.offsetWidth;
      badge.classList.add("uv-pop");
    }
    lastCount = count;

    // Isi drawer
    $("[data-cart-empty]").hidden = active;
    list.hidden = !active;
    $("[data-cart-footer]").hidden = !active;
    $("[data-cart-total]").textContent = rupiah(total);

    list.innerHTML = "";
    lines.forEach(function (l) {
      var node = itemTpl.content.firstElementChild.cloneNode(true);
      var img = $("[data-item-img]", node);
      img.src = l.img;
      img.alt = l.name;
      $("[data-item-category]", node).textContent = l.category;
      $("[data-item-name]", node).textContent = l.name;
      $("[data-item-qty]", node).textContent = String(l.qty);
      $("[data-item-total]", node).textContent = rupiah(l.price * l.qty);
      $("[data-item-dec]", node).addEventListener("click", function () { setQty(l.id, l.qty - 1); });
      $("[data-item-inc]", node).addEventListener("click", function () { setQty(l.id, l.qty + 1); });
      list.appendChild(node);
    });
  }

  function setCartOpen(open) {
    document.body.classList.toggle("cart-open", open);
    if (open) drawer.removeAttribute("inert"); else drawer.setAttribute("inert", "");
    if (lenis) { if (open) lenis.stop(); else lenis.start(); }
    if (open) $("[data-cart-close]", drawer).focus();
    else cartBtn.focus({ preventScroll: true });
  }

  if (cartBtn && drawer) {
    cartBtn.addEventListener("click", function () { setCartOpen(true); });
    $$("[data-cart-close]").forEach(function (el) {
      el.addEventListener("click", function () { setCartOpen(false); });
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && document.body.classList.contains("cart-open")) setCartOpen(false);
    });
    $$("[data-add-to-cart]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var id = btn.dataset.addToCart;
        setQty(id, (cart[id] || 0) + 1);
      });
    });
    $("[data-cart-clear]").addEventListener("click", function () {
      cart = {};
      saveCart();
      renderCart();
    });
    $("[data-cart-checkout]").addEventListener("click", function () {
      var lines = cartLines();
      var total = lines.reduce(function (s, l) { return s + l.qty * l.price; }, 0);
      var text = ["Halo Uvora, saya ingin memesan:"]
        .concat(lines.map(function (l) { return "• " + l.name + " x" + l.qty + " — " + rupiah(l.price * l.qty); }))
        .concat(["Total: " + rupiah(total)])
        .join("\n");
      openWhatsApp(text);
    });
    renderCart();
  }

  // ---------------------------------------------------------------- Consultation form
  var form = $("[data-consult-form]");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var f = form.elements;
      var text = [
        "Halo Uvora, saya ingin menjadwalkan konsultasi.",
        "Nama: " + f.name.value.trim(),
        "WhatsApp: " + f.phone.value.trim(),
        f.treatment.value.trim() && "Perawatan: " + f.treatment.value.trim(),
        f.message.value.trim() && "Keluhan: " + f.message.value.trim(),
      ].filter(Boolean).join("\n");
      openWhatsApp(text);
      form.reset();

      var label = $("[data-submit-label]", form);
      var original = label.textContent;
      label.textContent = "Terima kasih! Kami segera menghubungi Anda";
      setTimeout(function () { label.textContent = original; }, 4000);
    });
  }
})();
