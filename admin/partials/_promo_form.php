<?php
/**
 * admin/partials/_promo_form.php
 * Shared form fields for add/edit promo modals.
 * Included inside a <form> tag.
 */
?>
<div class="form-group">
  <label class="lbl">Judul Promo *</label>
  <input type="text" name="title" required placeholder="mis. Promo Lebaran 20%" oninput="autoSlug(this,'slug-promo')">
</div>
<div class="form-group">
  <label class="lbl">Slug (URL)</label>
  <input type="text" name="slug" id="slug-promo" placeholder="promo-lebaran-20pct">
</div>
<div class="form-group">
  <label class="lbl">Subtitle / Tagline</label>
  <input type="text" name="subtitle" placeholder="Hemat hingga 20% untuk semua perawatan wajah">
</div>
<div class="form-group">
  <label class="lbl">URL Banner (gambar)</label>
  <input type="text" name="banner" placeholder="assets/images/promo-xxx.jpg">
  <p class="hint">Relatif dari root, mis. <code>assets/images/promo-lebaran.jpg</code></p>
</div>
<div class="form-grid form-grid-2">
  <div class="form-group">
    <label class="lbl">Periode</label>
    <input type="text" name="period" placeholder="mis. 1–31 Maret 2025">
  </div>
  <div class="form-group">
    <label class="lbl">Kategori</label>
    <input type="text" name="category" placeholder="mis. Seasonal, Referral…">
  </div>
</div>
<div class="form-group">
  <label class="lbl">Deskripsi</label>
  <textarea name="description" rows="3" placeholder="Detail promo, apa yang didapat, syarat umum…"></textarea>
</div>
<div class="form-group">
  <label class="lbl">Syarat & Ketentuan</label>
  <textarea name="terms" rows="4" placeholder="Satu syarat per baris:
Berlaku untuk semua treatment wajah
Tidak dapat digabung dengan diskon lain
Khusus member terdaftar"></textarea>
  <p class="hint">Satu baris = satu item syarat & ketentuan.</p>
</div>
<div class="form-group">
  <label class="lbl">Urutan</label>
  <input type="number" name="sort_order" value="0" min="0">
</div>
<div class="form-group">
  <label class="toggle-wrap">
    <span class="toggle"><input type="checkbox" name="is_active" checked><span class="slider"></span></span>
    Aktif (tampil di website)
  </label>
</div>
