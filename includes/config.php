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

// Foto dokter masih sementara — ganti file 'img' dengan foto asli. 'exp' & 'tags' opsional.
$doctors = [
    [
        'name'      => 'dr. Nurul Hidayah',
        'role'      => 'Dokter Uvora Clinic',
        'img'       => 'assets/images/doctor-salsabila.jpg',
        'schedule'  => 'Senin – Sabtu · 10.00 – 18.00 WIB',
        'expertise' => [
            'Aesthetic Medicine',
            'Skin Rejuvenation & Anti-Aging',
            'Acne Management',
            'Chemical Peeling',
            'Skinbooster & Microneedling',
        ],
    ],
    [
        'name'      => 'dr. Nur Fatia Rahmawati',
        'role'      => 'Dokter Uvora Clinic',
        'img'       => 'assets/images/doctor-anindya.jpg',
        'schedule'  => 'Senin – Sabtu · 10.00 – 18.00 WIB',
        'expertise' => [
            'Aesthetic Medicine',
            'Laser & Light Therapy',
            'Body Contouring & Slimming',
            'Hair Restoration',
            'Botox & Filler',
        ],
    ],
];

$review_summary = ['rating' => '5.0', 'count' => '1.240+'];

$testimonials = [
    ['quote' => 'Pelayanan sangat profesional. Dokter menjelaskan setiap tahap perawatan dengan detail. Hasilnya melebihi ekspektasi.', 'name' => 'Kirana W.',   'city' => 'Jakarta Selatan'],
    ['quote' => 'Ruangannya bersih, steril, dan nyaman. Konsultasi tidak terburu-buru. Kulit saya jauh lebih cerah setelah 3 sesi.',    'name' => 'Dimas P.',    'city' => 'Bandung'],
    ['quote' => 'Klinik estetika terbaik yang pernah saya kunjungi. Timnya ramah dan sangat memahami kebutuhan kulit saya.',             'name' => 'Ayu Lestari', 'city' => 'Surabaya'],
];

// Katalog harga perawatan (perawatan.php). Kategori mengikuti price list.
// 'prefix' => tampil sebelum harga, mis. "Mulai".
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
        ['name' => 'Cauterisasi',      'price' => 179000, 'prefix' => 'Mulai'],
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
// Promosi — data dummy, nanti bisa dikelola dari admin panel.
$promotions = [
    [
        'slug'     => 'grand-opening-special',
        'title'    => 'Grand Opening Special',
        'subtitle' => 'Diskon 20% untuk Semua Perawatan',
        'banner'   => 'assets/images/promo/grand-opening.jpg',
        'period'   => '1 – 31 Oktober 2026',
        'category' => 'Diskon',
        'desc'     => 'Rayakan pembukaan Uvora Clinic dengan diskon spesial 20% untuk semua perawatan. Berlaku untuk kunjungan pertama Anda!',
        'terms'    => [
            'Diskon 20% berlaku untuk seluruh perawatan yang tersedia di Uvora Clinic.',
            'Promo hanya berlaku untuk kunjungan pertama (pasien baru).',
            'Wajib melakukan konsultasi dokter terlebih dahulu sebelum perawatan.',
            'Tidak dapat digabung dengan promo atau voucher lainnya.',
            'Berlaku untuk pembayaran tunai, debit, dan kartu kredit.',
            'Kuota terbatas, maksimal 50 pasien selama periode promo.',
            'Satu pasien hanya dapat menggunakan promo ini satu kali.',
            'Pendaftaran promo melalui WhatsApp atau langsung datang ke klinik.',
            'Pihak klinik berhak mengubah syarat dan ketentuan tanpa pemberitahuan sebelumnya.',
        ],
    ],
    [
        'slug'     => 'paket-hemat-hydrafacial',
        'title'    => 'Paket Hemat Hydrafacial',
        'subtitle' => '3 Sesi Hydrafacial — Hemat hingga Rp200.000',
        'banner'   => 'assets/images/promo/paket-hydrafacial.jpg',
        'period'   => '1 Oktober – 30 November 2026',
        'category' => 'Paket',
        'desc'     => 'Nikmati paket hemat 3 sesi Hydrafacial pilihan Anda dan hemat hingga Rp200.000 dari harga satuan.',
        'terms'    => [
            'Paket berlaku untuk 3 sesi Hydrafacial (Acne, Brightening, atau Salmon DNA).',
            'Sesi harus digunakan dalam waktu 3 bulan sejak pembelian paket.',
            'Pembayaran dilakukan di awal (full payment) saat membeli paket.',
            'Paket tidak dapat ditukar uang kembali (non-refundable).',
            'Jadwal perawatan harus dibuat melalui WhatsApp minimal H-1.',
            'Konsultasi dokter gratis di setiap sesi.',
            'Tidak dapat digabung dengan promo lainnya.',
            'Berlaku untuk semua metode pembayaran.',
            'Pengalihan paket ke orang lain tidak diperkenankan.',
        ],
    ],
    [
        'slug'     => 'referral-bonus',
        'title'    => 'Ajak Teman, Dapat Bonus!',
        'subtitle' => 'Bonus Rp100.000 untuk Kamu & Temanmu',
        'banner'   => 'assets/images/promo/referral-bonus.jpg',
        'period'   => 'Berlaku tanpa batas waktu',
        'category' => 'Referral',
        'desc'     => 'Ajak temanmu untuk perawatan di Uvora Clinic. Kamu dan temanmu masing-masing mendapat bonus Rp100.000.',
        'terms'    => [
            'Setiap pasien yang mereferensikan teman baru akan mendapat voucher Rp100.000.',
            'Teman yang direferensikan juga akan mendapat voucher Rp100.000 untuk kunjungan pertama.',
            'Voucher berlaku untuk semua perawatan dengan minimal transaksi Rp500.000.',
            'Teman yang direferensikan harus merupakan pasien baru Uvora Clinic.',
            'Voucher berlaku 30 hari sejak diterbitkan.',
            'Tidak ada batasan jumlah referral per pasien.',
            'Voucher tidak dapat ditukar uang tunai.',
            'Voucher tidak dapat digabung dengan promo lain.',
            'Informasikan kode referral ke kasir sebelum melakukan pembayaran.',
            'Pihak klinik berhak mengubah atau menghentikan program ini sewaktu-waktu.',
        ],
    ],
];

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
