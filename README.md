# Uvora Clinic — Landing Page (PHP)

Landing page statis berbasis PHP + HTML + CSS + JavaScript biasa. Tidak butuh Node.js,
database, atau proses build di server — cocok untuk shared hosting (Hostinger, dll).

## Struktur

```
index.php              Halaman utama (merangkai semua partial)
includes/config.php    SEMUA konten: teks, harga, dokter, testimoni, nomor WhatsApp
includes/icons.php     Ikon SVG
partials/*.php         Tiap section halaman
assets/css/style.css   CSS jadi (hasil compile Tailwind) — ini yang dipakai browser
assets/js/main.js      Animasi, keranjang, slider before/after, form → WhatsApp
assets/js/lenis.min.js Smooth scroll
assets/images/         Gambar
tailwind/              Sumber CSS (hanya untuk compile ulang, tidak wajib di-upload)
```

## Mengubah konten

Edit `includes/config.php` saja — nomor WhatsApp, alamat, jam buka, daftar perawatan,
produk (harga dalam angka), dokter, testimoni, dan foto before/after.

## Deploy ke Hostinger

1. Buka hPanel → **File Manager** → masuk ke `public_html`.
2. Upload semua isi folder ini **kecuali** `Emergent _ Fullstack App*`, `tailwind/`, `.claude/`, dan `README.md`.
   (Atau zip dulu, upload, lalu Extract di File Manager.)
3. Pastikan `index.php` langsung berada di dalam `public_html`.
4. Buka domain Anda. Selesai.

PHP 7.4 ke atas sudah cukup.

## Menambah class Tailwind baru (opsional)

`assets/css/style.css` sudah berisi semua class yang dipakai sekarang. Kalau nanti Anda
menambah class Tailwind **baru** di file PHP/JS, compile ulang CSS di komputer lokal
memakai Tailwind Standalone CLI (satu file .exe, tanpa Node.js):

1. Download `tailwindcss-windows-x64.exe` versi **v3.4.x** dari
   https://github.com/tailwindlabs/tailwindcss/releases (cari rilis v3.4.17), taruh di folder project.
2. Jalankan dari folder project:

   ```
   tailwindcss-windows-x64.exe -c tailwind/tailwind.config.js -i tailwind/input.css -o assets/css/style.css --minify
   ```

3. Upload ulang `assets/css/style.css`.

## Menjalankan di komputer lokal

Dengan XAMPP: `C:\xampp\php\php.exe -S localhost:8000` dari folder project, lalu buka http://localhost:8000
