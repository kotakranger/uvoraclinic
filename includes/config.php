<?php
// Semua konten landing page ada di sini supaya mudah diganti tanpa menyentuh markup.

$site = [
    'name'            => 'Uvora Clinic',
    'title'           => 'Uvora Clinic · Aesthetic Medicine',
    'description'     => 'Uvora Clinic — klinik estetika medis premium untuk kulit sehat dan bercahaya secara alami.',
    'whatsapp'        => '6281210002000', // format internasional tanpa "+"
    'opening_hours'   => 'Senin – Sabtu · 09.00 – 20.00 WIB',
    'address'         => 'Jl. Senopati Raya No. 88, Kebayoran Baru, Jakarta Selatan 12190',
    'phone'           => '+62 812-1000-2000',
];

$nav_links = [
    ['label' => 'Perawatan', 'href' => '#perawatan'],
    ['label' => 'Dokter',    'href' => '#dokter'],
    ['label' => 'Hasil',     'href' => '#hasil'],
    ['label' => 'Testimoni', 'href' => '#testimoni'],
    ['label' => 'Lokasi',    'href' => '#lokasi'],
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

// Hanya data "acne" yang ada di sumber asli. Tiga lainnya memakai foto yang sama —
// ganti 'before' / 'after' dengan foto pasien yang sesuai.
$concerns = [
    ['id' => 'acne',       'label' => 'Jerawat & Bekas', 'treatment' => 'Acne Medical Solution', 'text' => 'Direkomendasikan 4–6 sesi dengan chemical peeling bertahap.',              'before' => 'assets/images/before-acne.jpg', 'after' => 'assets/images/after-acne.jpg'],
    ['id' => 'pigment',    'label' => 'Pigmentasi',      'treatment' => 'Laser Rejuvenation',    'text' => 'Direkomendasikan 3–5 sesi picosecond laser dengan jarak 4 minggu.',       'before' => 'assets/images/before-acne.jpg', 'after' => 'assets/images/after-acne.jpg'],
    ['id' => 'tightening', 'label' => 'Kulit Kendur',    'treatment' => 'Anti-Aging Program',    'text' => 'Direkomendasikan 2–3 sesi lifting non-bedah dengan terapi kolagen.',      'before' => 'assets/images/before-acne.jpg', 'after' => 'assets/images/after-acne.jpg'],
    ['id' => 'glow',       'label' => 'Kulit Kusam',     'treatment' => 'Skin Booster',          'text' => 'Direkomendasikan 3 sesi skin booster untuk hidrasi dan kilau alami.',     'before' => 'assets/images/before-acne.jpg', 'after' => 'assets/images/after-acne.jpg'],
];

$concern_benefits = [
    'Ditangani dokter spesialis',
    'Protokol medis terukur & bertahap',
    'Tanpa waktu pemulihan panjang',
];

$doctors = [
    ['name' => 'dr. Anindya Paramitha, Sp.DVE', 'role' => 'Dermatovenereologi Estetika', 'exp' => '12+ tahun', 'tags' => ['Laser', 'Anti-Aging'],       'img' => 'assets/images/doctor-anindya.jpg'],
    ['name' => 'dr. Raka Wijanarko, Sp.DVE',    'role' => 'Bedah Kulit & Kontur Wajah',  'exp' => '10+ tahun', 'tags' => ['Contouring', 'Filler'],      'img' => 'assets/images/doctor-raka.jpg'],
    ['name' => 'dr. Salsabila Hartono, M.Ked',  'role' => 'Dokter Estetika Klinis',      'exp' => '8+ tahun',  'tags' => ['Acne', 'Skin Booster'],      'img' => 'assets/images/doctor-salsabila.jpg'],
];

$review_summary = ['rating' => '5.0', 'count' => '1.240+'];

$testimonials = [
    ['quote' => 'Pelayanan sangat profesional. Dokter menjelaskan setiap tahap perawatan dengan detail. Hasilnya melebihi ekspektasi.', 'name' => 'Kirana W.',   'city' => 'Jakarta Selatan'],
    ['quote' => 'Ruangannya bersih, steril, dan nyaman. Konsultasi tidak terburu-buru. Kulit saya jauh lebih cerah setelah 3 sesi.',    'name' => 'Dimas P.',    'city' => 'Bandung'],
    ['quote' => 'Klinik estetika terbaik yang pernah saya kunjungi. Timnya ramah dan sangat memahami kebutuhan kulit saya.',             'name' => 'Ayu Lestari', 'city' => 'Surabaya'],
];

$footer_treatments = ['Laser Rejuvenation', 'Anti-Aging', 'Acne Solution', 'Skin Booster'];
$footer_hours      = ['Senin – Jumat · 09.00 – 20.00', 'Sabtu · 09.00 – 18.00', 'Minggu · Tutup'];
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
