# Uvora Clinic — Website & Admin Panel (PHP + MySQL)

Website klinik + panel admin. PHP 7.4+ (disarankan 8.x), MySQL/MariaDB, ekstensi **GD** (resize foto, dukungan WebP).
Tanpa Node.js dan tanpa proses build — cocok untuk shared hosting (Hostinger, dll).

> ⚠️ **PENTING SEBELUM GO LIVE**: Lihat dan ikuti [DEPLOY_CHECKLIST.md](file:///Users/mac/Development/Uvoraclinic/uvoraclinic/DEPLOY_CHECKLIST.md) untuk langkah-langkah persiapan sebelum dan sesudah upload ke hosting.

## Struktur folder

```
index.php, perawatan.php, perawatan-detail.php, promo.php, promo-detail.php   Halaman publik
partials/        Potongan section halaman (header, hero, hasil, dokter, dst.)
assets/
  css/, js/      Stylesheet & script
  images/        Gambar statis situs (hero, stok perawatan, produk)
  uploads/       Foto hasil upload admin (otomatis di-resize; .htaccess memblokir eksekusi PHP)
admin/           Panel admin (login, layanan, promo, hasil, dokter & konten, konsultasi)
  partials/      Potongan form admin
api/consult.php  Endpoint form konsultasi
includes/        Logika bersama (config, db, data, catalog, uploader, csrf) — diblokir dari akses browser
setup/           migrate.php + seed.php — HANYA dipakai sekali saat instalasi, lalu HAPUS dari hosting
```

## Mengelola konten (Admin → /admin)

| Menu | Isi |
|---|---|
| Konsultasi | Pesan dari form konsultasi |
| Layanan | Kategori (popup) + layanan (halaman tambah/edit): harga, foto utama + 2 foto galeri, cara kerja, cocok untuk, catatan, alur |
| Promo | Banner & syarat promo (halaman tambah/edit) |
| Hasil | Foto before/after (halaman tambah/edit) — section "Hasil" tampil otomatis bila ada data |
| Dokter & Konten | Dokter, testimoni, reels TikTok/IG/YouTube |

- Urutan tampil otomatis: item terbaru berada di urutan terakhir.
- Section yang belum punya data (promo, hasil, testimoni, dokter) otomatis disembunyikan beserta link menunya.
- Upload foto: JPG/PNG/WebP maks. 5 MB, otomatis diperkecil (sisi terpanjang 1200 px) & dikompres. Bisa juga isi URL.
- Teks umum (nomor WhatsApp, alamat, jam buka, sosial media) ada di `includes/config.php`.

## Deploy ke hosting

1. **Buat database MySQL** di hPanel, catat host, nama DB, user, password.
2. **Upload semua file** ke `public_html` (zip lalu Extract di File Manager lebih cepat). Pastikan `index.php` langsung ada di `public_html`.
3. **Salin** `includes/db_config.example.php` → `includes/db_config.php`, isi kredensial database. (File ini tidak ikut git.)
4. **Buat tabel**: buka `https://domain-anda/setup/migrate.php?run=1`.
   **Isi daftar layanan awal** (opsional, hanya jika tabel layanan kosong): `https://domain-anda/setup/seed.php?run=1`.
5. **Hapus folder `setup/`** dari hosting.
6. **Ganti password admin**: buat hash dengan
   `php -r "echo password_hash('PASSWORD-BARU', PASSWORD_BCRYPT);"`
   lalu isi `ADMIN_PASSWORD_HASH` di `admin/auth.php`. (Password bawaan **harus** diganti sebelum live.)
7. Pastikan folder `assets/uploads/` dapat ditulis (permission 755/775).
8. Login di `https://domain-anda/admin/` lalu isi dokter, promo, hasil, dan foto layanan.

Batas upload PHP (`upload_max_filesize`, `post_max_size`) sebaiknya ≥ 8 MB.

## Menjalankan lokal

```
php -S localhost:8000
```
lalu buka http://localhost:8000 (admin di /admin/).
