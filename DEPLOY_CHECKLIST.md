# 🚀 Checklist Deploy ke Hosting — Uvora Clinic

> **PRIORITAS TINGGI**: Selesaikan item-item di checklist ini sebelum dan sesudah website diunggah ke server hosting (cPanel / Hostinger / dsb).

---

## 1. Sebelum Upload ke Hosting (Persiapan Lokal)

- [ ] **Ganti Password Admin Default**
  - Password bawaan saat ini: `uvora2026` (Wajib diganti sebelum live).
  - Buka terminal dan jalankan:
    ```bash
    php -r "echo password_hash('PasswordBaruYangKuat', PASSWORD_BCRYPT);"
    ```
  - Salin string hash yang dihasilkan (berawalan `$2y$12$...`).
  - Tempelkan ke konstanta `ADMIN_PASSWORD_HASH` di file [`admin/auth.php`](admin/auth.php).

- [ ] **Siapkan File Kredensial Database Hosting**
  - Salin [`includes/db_config.example.php`](includes/db_config.example.php) menjadi `includes/db_config.php`.
  - Isi nama database, username, dan password MySQL dari panel hosting Anda.
  - *Catatan: File `includes/db_config.php` otomatis diabaikan git demi keamanan.*

---

## 2. Proses Upload ke Hosting

- [ ] **Upload File Website**
  - Arsipkan seluruh isi project (zip) lalu ekstrak di dalam direktori root web hosting (biasanya `public_html`).
  - Pastikan file `index.php` berada langsung di `public_html/index.php`.

- [ ] **Pastikan Izin Tulis Folder Uploads**
  - Pastikan folder `assets/uploads/` dan subfoldernya (`doctors`, `layanan`, `promos`, `hasil`) memiliki izin tulis (permission `755` atau `775`).
  - Pastikan modul PHP **GD** aktif di hosting (standar di Hostinger/cPanel).

---

## 3. Setup Database di Hosting

- [ ] **Jalankan Migrasi Skrip Database**
  - Buka URL ini di browser Anda:
    ```text
    https://domain-anda.com/setup/migrate.php?run=1
    ```
  - Pastikan semua tabel berhasil dibuat dengan tanda centang hijau (`✓ Table ... ready`).

- [ ] **Import Katalog Layanan (27 item, 7 kategori)**
  - Di phpMyAdmin pilih database Anda → tab **Import** → pilih `setup/layanan_data.sql`.
  - Hanya jalankan jika tabel layanan masih kosong. Foto layanan "Acne Hydrafacial" perlu di-upload ulang lewat admin.

- [ ] **Wajib: Hapus Folder `setup/` dari Hosting**
  - Setelah migrasi berhasil, segera hapus folder `setup/` dari File Manager hosting Anda agar tidak dapat diakses publik.

---

## 4. Verifikasi Akhir & Pengisian Konten

- [ ] **Login Admin Panel**
  - Buka `https://domain-anda.com/admin/login.php`
  - Masuk dengan username: `admin` dan password baru Anda.

- [ ] **Input Konten Pertama via Admin**
  - Tambahkan minimal 1 data di menu **Hasil (Before/After)** (agar section Galeri Transformasi muncul di beranda).
  - Tambahkan data dokter dan jadwal praktik di menu **Dokter & Konten**.
  - Masukkan promo aktif di menu **Promo**.
  - Testimoni dan reels video dapat diisi bertahap sesuai kebutuhan.

- [ ] **Uji Form Konsultasi WhatsApp**
  - Buka halaman utama website, coba kirim pesan konsultasi untuk memastikan nomor WhatsApp klinik di `includes/config.php` sudah benar dan pesan masuk ke admin inbox.
