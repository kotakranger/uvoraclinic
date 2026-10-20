<?php /* admin/partials/_layanan_detail_form.php — foto galeri & detail halaman perawatan. Expects $row (array, may be empty). */
$v = $row ?? []; ?>
<?php image_field('img2', 'Foto 2 (galeri)', '', $v['img2'] ?? ''); ?>
<?php image_field('img3', 'Foto 3 (galeri)', '', $v['img3'] ?? ''); ?>
<p class="hint" style="margin:-6px 0 14px">Foto Utama + Foto 2 + Foto 3 tampil sebagai galeri di halaman detail. Kolom URL juga menerima link YouTube atau file video (.mp4).</p>

<div class="form-group">
  <label class="lbl">Cara Kerja — Judul</label>
  <input type="text" name="how_title" value="<?= he($v['how_title'] ?? '') ?>" placeholder="mis. Membersihkan, mengangkat, lalu menutrisi">
</div>
<div class="form-group">
  <label class="lbl">Cara Kerja — Penjelasan</label>
  <textarea name="how_text" rows="3" placeholder="Jelaskan singkat bagaimana perawatan ini bekerja…"><?= he($v['how_text'] ?? '') ?></textarea>
</div>
<div class="form-group">
  <label class="lbl">Cara Kerja — Poin Keunggulan</label>
  <textarea name="features" rows="4" placeholder="Satu poin per baris"><?= he($v['features'] ?? '') ?></textarea>
</div>
<div class="form-group">
  <label class="lbl">Cocok Untuk</label>
  <textarea name="suitable" rows="4" placeholder="Satu kondisi per baris"><?= he($v['suitable'] ?? '') ?></textarea>
</div>
<div class="form-group">
  <label class="lbl">Catatan Perawatan</label>
  <textarea name="notes" rows="4" placeholder="Satu catatan per baris"><?= he($v['notes'] ?? '') ?></textarea>
</div>
<div class="form-group">
  <label class="lbl">Alur Perawatan</label>
  <textarea name="steps" rows="5" placeholder="Satu tahap per baris, format: Judul | Penjelasan&#10;Konsultasi | Dokter menilai kondisi kulit Anda&#10;Tindakan | Perawatan dilakukan sesuai protokol"><?= he($v['steps'] ?? '') ?></textarea>
  <p class="hint">Format <code>Judul | Penjelasan</code>. Kolom yang dikosongkan memakai teks bawaan sistem.</p>
</div>
