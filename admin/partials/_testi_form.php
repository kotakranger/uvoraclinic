<?php /* admin/partials/_testi_form.php — shared testimonial form fields */ ?>
<div class="form-group">
  <label class="lbl">Kutipan / Testimoni *</label>
  <textarea name="quote" required rows="4" placeholder="Kulit saya jauh lebih cerah setelah perawatan di Uvora Clinic! Dokternya ramah dan profesional…"></textarea>
</div>
<div class="form-group">
  <label class="lbl">Nama Pelanggan *</label>
  <input type="text" name="author" required placeholder="Dewi A.">
</div>
<div class="form-group">
  <label class="lbl">Kota</label>
  <input type="text" name="city" placeholder="Bekasi">
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
