# Uvora Clinic — Landing Page (PHP)

Landing page berbasis PHP + HTML + CSS + JavaScript biasa. Tidak butuh Node.js,
database, atau proses build — cocok untuk shared hosting (Hostinger, dll).

## Struktur

```
index.php              Halaman utama (merangkai semua partial)
includes/config.php    SEMUA konten: teks, harga, dokter, testimoni, nomor WhatsApp
includes/icons.php     Ikon SVG
partials/*.php         Tiap section halaman
assets/css/style.css   Stylesheet
assets/js/main.js      Animasi, keranjang, slider before/after, form → WhatsApp
assets/js/lenis.min.js Smooth scroll
assets/images/         Gambar
```

## Mengubah konten

Edit `includes/config.php` saja — nomor WhatsApp, alamat, jam buka, daftar perawatan,
produk (harga dalam angka), dokter, testimoni, dan foto before/after.

## Deploy ke Hostinger

1. Buka hPanel → **File Manager** → masuk ke `public_html`.
2. Upload semua isi repo ini (boleh tanpa `README.md`).
   (Atau zip dulu, upload, lalu Extract di File Manager.)
3. Pastikan `index.php` langsung berada di dalam `public_html`.
4. Buka domain Anda. Selesai.

PHP 7.4 ke atas sudah cukup.

## Menjalankan di komputer lokal

Dengan XAMPP: `C:\xampp\php\php.exe -S localhost:8000` dari folder project, lalu buka http://localhost:8000
