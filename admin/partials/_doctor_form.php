<?php /* admin/partials/_doctor_form.php — shared doctor form fields */ ?>
<div class="form-group">
  <label class="lbl">Nama Dokter *</label>
  <input type="text" name="name" required placeholder="dr. Nama Lengkap, SpKK">
</div>
<div class="form-group">
  <label class="lbl">Jabatan / Spesialisasi</label>
  <input type="text" name="role" placeholder="Dermatolog & Dokter Estetika">
</div>
<div class="form-group">
  <label class="lbl">URL Foto</label>
  <input type="text" name="img" placeholder="assets/images/doctor-xxx.jpg">
  <p class="hint">Relatif dari root, mis. <code>assets/images/doctor-sari.jpg</code></p>
</div>
<div class="form-group">
  <label class="lbl">Jadwal Praktik</label>
  <input type="text" name="schedule" placeholder="Senin – Jumat, 10.00–17.00">
</div>
<div class="form-group">
  <label class="lbl">Keahlian</label>
  <textarea name="expertise" rows="3" placeholder="Satu keahlian per baris:
Laser Rejuvenation
Anti-Aging Therapy
Acne Treatment"></textarea>
  <p class="hint">Satu baris = satu item keahlian.</p>
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
