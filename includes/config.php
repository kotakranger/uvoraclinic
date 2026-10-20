<?php
// Semua konten landing page ada di sini supaya mudah diganti tanpa menyentuh markup.

$site = [
    'name'            => 'Uvora Clinic',
    'title'           => 'Uvora Clinic · Aesthetic Medicine',
    'description'     => 'Uvora Clinic — klinik estetika medis premium untuk kulit sehat dan bercahaya secara alami.',
    'whatsapp'        => '6285139372484', // format internasional tanpa "+"
    'opening_hours'   => 'Setiap hari · 10.00 – 18.00 WIB',
    'address'         => 'Ruko Grand Galaxy City Blok RSOD No.10, RT.004/RW.019, Jaka Setia, Kec. Bekasi Selatan, Kota Bekasi, Jawa Barat 17147',
    'phone'           => '0851-3937-2484',
    'maps_url'        => 'https://www.google.com/maps/place/UVORA+Clinic/@-6.2717052,106.9706513,17z/data=!3m1!4b1!4m6!3m5!1s0x6f8fc4baa6f47dbb:0xe3af588e601fa651!8m2!3d-6.2717052!4d106.9706513!16s%2Fg%2F11zd8006l1',
    'maps_embed'      => 'https://maps.google.com/maps?q=-6.2717052,106.9706513&z=17&output=embed',
];

$nav_links = [
    ['label' => 'Perawatan', 'href' => 'perawatan.php'],
    ['label' => 'Promo',     'href' => 'promo.php'],
    ['label' => 'Dokter',    'href' => 'index.php#dokter'],
    ['label' => 'Hasil',     'href' => 'index.php#hasil'],
    ['label' => 'Testimoni', 'href' => 'index.php#testimoni'],
    ['label' => 'Lokasi',    'href' => 'index.php#lokasi'],
];

$trust_points = [
    ['icon' => 'shield-check', 'title' => 'Dokter Bersertifikat', 'text' => 'Dermatolog & dokter estetika berlisensi resmi KKI.'],
    ['icon' => 'badge-check',  'title' => 'Alat Berstandar FDA',  'text' => 'Teknologi perawatan tersertifikasi FDA & CE.'],
    ['icon' => 'user-cog',     'title' => 'Perawatan Personal',   'text' => 'Protokol dirancang sesuai kondisi kulit Anda.'],
    ['icon' => 'sparkles',     'title' => 'Ruang Steril',         'text' => 'Standar sterilisasi medis di setiap ruangan.'],
];

$treatments = [
    [
        'tag'      => 'Signature',
        'name'     => 'Laser Rejuvenation',
        'desc'     => 'Meremajakan kulit dengan teknologi picosecond laser untuk tekstur halus dan merata.',
        'price'    => 'Mulai Rp 1.500.000',
        'duration' => '45 menit / sesi',
        'img'      => 'assets/images/treatment-laser.jpg',
    ],
    [
        'tag'      => 'Populer',
        'name'     => 'Anti-Aging Program',
        'desc'     => 'Kombinasi terapi kolagen dan lifting non-bedah untuk mengembalikan kekencangan kulit.',
        'price'    => 'Mulai Rp 2.200.000',
        'duration' => '60 menit / sesi',
        'img'      => 'assets/images/treatment-antiaging.jpg',
    ],
    [
        'tag'      => 'Klinis',
        'name'     => 'Acne Medical Solution',
        'desc'     => 'Penanganan jerawat aktif secara medis dengan pengawasan dokter spesialis kulit.',
        'price'    => 'Mulai Rp 950.000',
        'duration' => '40 menit / sesi',
        'img'      => 'assets/images/treatment-acne.jpg',
    ],
];

$products = [
    ['id' => 'p1', 'category' => 'Serum',       'name' => 'Radiance Repair Serum',    'desc' => 'Vitamin C + niacinamide untuk kulit cerah merata.',       'price' => 480000, 'img' => 'assets/images/product-serum.jpg'],
    ['id' => 'p2', 'category' => 'Moisturizer', 'name' => 'Barrier Recovery Cream',   'desc' => 'Melembapkan & memperkuat skin barrier sepanjang hari.',   'price' => 395000, 'img' => 'assets/images/product-cream.jpg'],
    ['id' => 'p3', 'category' => 'Treatment',   'name' => 'Overnight Retinol Elixir', 'desc' => 'Retinol terenkapsulasi untuk regenerasi sel malam hari.', 'price' => 620000, 'img' => 'assets/images/product-retinol.jpg'],
    ['id' => 'p4', 'category' => 'Essence',     'name' => 'Hydra Glow Essence',       'desc' => 'Hyaluronic acid berlapis untuk hidrasi mendalam.',        'price' => 350000, 'img' => 'assets/images/product-essence.jpg'],
];

// Hasil before/after — dikelola dari Admin → Hasil.
$concerns = [];

$concern_benefits = [
    'Ditangani dokter spesialis',
    'Protokol medis terukur & bertahap',
    'Tanpa waktu pemulihan panjang',
];

// Foto dokter masih sementara — ganti file 'img' dengan foto asli. 'exp' & 'tags' opsional.
// Dokter — dikelola dari Admin → Dokter & Konten.
$doctors = [];

// Isi untuk menampilkan ringkasan rating di atas testimoni, mis. ['rating' => '4.9', 'count' => '120']. null = disembunyikan.
$review_summary = null;

// Testimoni — dikelola dari Admin → Dokter & Konten.
$testimonials = [];

// Katalog harga perawatan (perawatan.php). Kategori mengikuti price list.
$treatment_categories = [
    'hydrafacial' => ['label' => 'Hydrafacial', 'items' => [
        ['name' => 'Acne Hydrafacial',        'price' => 259000],
        ['name' => 'Brightening Hydrafacial', 'price' => 259000],
        ['name' => 'Salmon DNA Hydrafacial',  'price' => 429000],
    ]],
    'peeling' => ['label' => 'Peeling Treatments', 'items' => [
        ['name' => 'Brightening Peel',  'price' => 259000],
        ['name' => 'Acne Peel',         'price' => 259000],
        ['name' => 'Lhala Peel',        'price' => 399000],
        ['name' => 'Lip Radiance Peel', 'price' => 399000],
        ['name' => 'Eye Radiance Peel', 'price' => 399000],
    ]],
    'microneedling' => ['label' => 'Microneedling', 'items' => [
        ['name' => 'Exosome Microneedle',       'price' => 899000],
        ['name' => 'Microneedle Salmon DNA',    'price' => 899000],
        ['name' => 'Microneedle Salmon DNA Eye', 'price' => 429000],
    ]],
    'skinbooster' => ['label' => 'Skinbooster', 'items' => [
        ['name' => 'Acne Skinbooster',       'price' => 899000],
        ['name' => 'Salmon DNA Polybooster', 'price' => 1899000],
        ['name' => 'Salmon DNA Booster',     'price' => 1699000],
        ['name' => 'Salmon DNA Eyebooster',  'price' => 1699000],
        ['name' => 'Rejuran Healer',         'price' => 3999000],
        ['name' => 'Rejuran HB',             'price' => 3699000],
    ]],
    'hair' => ['label' => 'Hair Treatments', 'items' => [
        ['name' => 'Hair Restoration Therapy', 'price' => 1899000],
    ]],
    'slimming' => ['label' => 'Slimming', 'items' => [
        ['name' => 'V-Line LipoSculpt',   'price' => 429000],
        ['name' => 'BodySculpt Injection', 'price' => 689000],
        ['name' => 'Uvora Face Define',   'price' => 429000],
        ['name' => 'Uvora Body Define',   'price' => 689000],
    ]],
    'lainnya' => ['label' => 'Lainnya', 'items' => [
        ['name' => 'Cauterisasi',      'price' => 179000],
        ['name' => 'Botox 100 UI',     'price' => 4899000],
        ['name' => 'Mounjaro 2,5 mg',  'price' => 4589000],
        ['name' => 'Mounjaro 5 mg',    'price' => 5219000],
        ['name' => 'Collagen Therapy', 'price' => 7899000],
    ]],
];
$treatment_note   = 'Gratis konsultasi dokter · Personalized treatment plans are available after consultation.';
$consult_badge    = 'Free Doctor Consultation';

// Pembagian section berdasarkan harga ('max' = batas atas, tidak termasuk). Tier ditentukan otomatis dari harga.
$price_tiers = [
    ['no' => '01', 'name' => 'Low Tier',  'range' => 'Harga di bawah Rp500.000',      'max' => 500000],
    ['no' => '02', 'name' => 'Mid Tier',  'range' => 'Harga Rp500.000–Rp999.000',   'max' => 1000000],
    ['no' => '03', 'name' => 'High Tier', 'range' => 'Harga mulai Rp1.000.000',       'max' => PHP_INT_MAX],
];

// Galeri foto & video di halaman detail (gaya marketplace: thumbnail bisa diganti-ganti).
// Setiap perawatan punya beberapa gambar supaya galeri muncul seperti di Tokopedia.
// Ganti dengan foto asli klinik saat tersedia.
$treatment_media = [
    // Hydrafacial
    'acne-hydrafacial'        => ['assets/images/treatment-acne.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-booster.jpg'],
    'brightening-hydrafacial' => ['assets/images/treatment-facial.jpg', 'assets/images/treatment-peel.jpg', 'assets/images/treatment-booster.jpg'],
    'salmon-dna-hydrafacial'  => ['assets/images/treatment-facial.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-peel.jpg'],
    // Peeling
    'brightening-peel'  => ['assets/images/treatment-peel.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-booster.jpg'],
    'acne-peel'         => ['assets/images/treatment-acne.jpg', 'assets/images/treatment-peel.jpg', 'assets/images/treatment-facial.jpg'],
    'lhala-peel'        => ['assets/images/treatment-peel.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg'],
    'lip-radiance-peel' => ['assets/images/treatment-peel.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-antiaging.jpg'],
    'eye-radiance-peel' => ['assets/images/treatment-peel.jpg', 'assets/images/treatment-antiaging.jpg', 'assets/images/treatment-booster.jpg'],
    // Microneedling
    'exosome-microneedle'        => ['assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-peel.jpg'],
    'microneedle-salmon-dna'     => ['assets/images/treatment-booster.jpg', 'assets/images/treatment-peel.jpg', 'assets/images/treatment-facial.jpg'],
    'microneedle-salmon-dna-eye' => ['assets/images/treatment-booster.jpg', 'assets/images/treatment-antiaging.jpg', 'assets/images/treatment-peel.jpg'],
    // Skinbooster
    'acne-skinbooster'        => ['assets/images/treatment-acne.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg'],
    'salmon-dna-polybooster'  => ['assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-antiaging.jpg'],
    'salmon-dna-booster'      => ['assets/images/treatment-booster.jpg', 'assets/images/treatment-antiaging.jpg', 'assets/images/treatment-peel.jpg'],
    'salmon-dna-eyebooster'   => ['assets/images/treatment-booster.jpg', 'assets/images/treatment-peel.jpg', 'assets/images/treatment-facial.jpg'],
    'rejuran-healer'          => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg'],
    'rejuran-hb'              => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-booster.jpg'],
    // Hair
    'hair-restoration-therapy' => ['assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-peel.jpg'],
    // Slimming
    'v-line-liposculpt'    => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg'],
    'bodysculpt-injection' => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-peel.jpg', 'assets/images/treatment-booster.jpg'],
    'uvora-face-define'    => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-peel.jpg'],
    'uvora-body-define'    => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-peel.jpg'],
    // Lainnya
    'cauterisasi'      => ['assets/images/treatment-laser.jpg', 'assets/images/treatment-peel.jpg', 'assets/images/treatment-facial.jpg'],
    'botox-100-ui'     => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-facial.jpg'],
    'mounjaro-2-5-mg'  => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-booster.jpg', 'assets/images/treatment-peel.jpg'],
    'mounjaro-5-mg'    => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-peel.jpg', 'assets/images/treatment-booster.jpg'],
    'collagen-therapy' => ['assets/images/treatment-antiaging.jpg', 'assets/images/treatment-facial.jpg', 'assets/images/treatment-booster.jpg'],
];

// ---------------------------------------------------------------------------
// Promo — dikelola dari Admin → Promo.
$promotions = [];

// ---------------------------------------------------------------------------
// Content Reels — video pendek portrait (TikTok / IG Reels / YouTube Shorts).
// Dikelola dari Admin → Dokter & Konten.
// 'embed_url' bisa berupa:
//   - Instagram: https://www.instagram.com/p/{shortcode}/embed/
//   - TikTok:    https://www.tiktok.com/embed/v2/{id}
//   - YouTube:   https://www.youtube.com/embed/{id}?shorts=1
$content_reels_follow = ['label' => 'Ikuti kami di TikTok', 'href' => 'https://www.tiktok.com/@indaha.l'];
// Reels — dikelola dari Admin → Dokter & Konten.
$content_reels = [];

$footer_treatments = ['Hydrafacial', 'Peeling Treatments', 'Microneedling', 'Skinbooster'];

$footer_hours      = ['Senin – Minggu · 10.00 – 18.00'];
$socials = [
    ['icon' => 'instagram', 'label' => 'Instagram', 'href' => '#'],
    ['icon' => 'facebook',  'label' => 'Facebook',  'href' => '#'],
];

// ---------------------------------------------------------------------------
// Helpers

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function whatsapp_link($text = '')
{
    global $site;
    $url = 'https://wa.me/' . $site['whatsapp'];
    return $text !== '' ? $url . '?text=' . rawurlencode($text) : $url;
}
