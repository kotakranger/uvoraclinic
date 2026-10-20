<?php
// Data detail katalog perawatan. Kategori & harga ada di config.php ($treatment_categories);
// file ini menambahkan jenis, durasi, deskripsi, dan isi halaman detail.
// Durasi & deskripsi bersifat umum — sesuaikan dengan protokol klinik.

$treatment_image = [
    'facial'   => 'assets/images/treatment-facial.jpg',
    'peel'     => 'assets/images/treatment-peel.jpg',
    'booster'  => 'assets/images/treatment-booster.jpg',
    'botox'    => 'assets/images/treatment-antiaging.jpg',
    'program'  => 'assets/images/treatment-antiaging.jpg',
    'cauter'   => 'assets/images/treatment-laser.jpg',
    'hair'     => 'assets/images/treatment-booster.jpg',
    'slimming' => 'assets/images/treatment-antiaging.jpg',
];

// nama => [jenis, durasi, deskripsi, (gambar sampul opsional)]
$treatment_meta = [
    'Acne Hydrafacial'        => ['facial', '45 menit', 'Membersihkan pori, mengangkat sebum berlebih, dan menenangkan kulit berjerawat dalam satu sesi.', 'assets/images/treatment-acne.jpg'],
    'Brightening Hydrafacial' => ['facial', '45 menit', 'Pembersihan mendalam, eksfoliasi lembut, dan infus serum pencerah untuk kulit tampak segar dan bercahaya.'],
    'Salmon DNA Hydrafacial'  => ['facial', '50 menit', 'Hydrafacial yang dilengkapi serum Salmon DNA untuk membantu melembapkan dan menunjang regenerasi kulit.'],
    'Brightening Peel'        => ['peel', '30 menit', 'Chemical peeling ringan untuk membantu mencerahkan kulit kusam dan meratakan warna kulit.'],
    'Acne Peel'               => ['peel', '30 menit', 'Peeling khusus kulit berjerawat untuk membantu mengurangi sumbatan pori dan jerawat aktif.', 'assets/images/treatment-acne.jpg'],
    'Lhala Peel'              => ['peel', '30 menit', 'Peeling dengan formula Lhala untuk membantu memperbaiki tekstur dan menyamarkan noda kulit.'],
    'Lip Radiance Peel'       => ['peel', '20 menit', 'Perawatan area bibir untuk membantu mengangkat sel kulit mati dan membuat bibir tampak lebih cerah dan lembut.'],
    'Eye Radiance Peel'       => ['peel', '20 menit', 'Peeling area sekitar mata untuk membantu mencerahkan kulit yang tampak gelap atau lelah.'],
    'Exosome Microneedle'     => ['booster', '50 menit', 'Microneedle dengan exosome untuk menunjang pemulihan dan regenerasi kulit.'],
    'Microneedle Salmon DNA'  => ['booster', '50 menit', 'Microneedle dengan Salmon DNA untuk membantu memperbaiki tekstur, hidrasi, dan kualitas kulit wajah.'],
    'Microneedle Salmon DNA Eye' => ['booster', '45 menit', 'Microneedle dengan Salmon DNA khusus area mata untuk membantu memperbaiki tekstur dan kelembapan kulit.'],
    'Acne Skinbooster'        => ['booster', '45 menit', 'Skin booster untuk membantu menenangkan kulit berjerawat sekaligus menunjang pemulihan kulit.', 'assets/images/treatment-acne.jpg'],
    'Salmon DNA Polybooster'  => ['booster', '50 menit', 'Kombinasi polinukleotida Salmon DNA untuk membantu memperbaiki kualitas kulit secara menyeluruh.'],
    'Salmon DNA Booster'      => ['booster', '45 menit', 'Injeksi Salmon DNA ke lapisan kulit untuk membantu hidrasi, elastisitas, dan kilau alami.'],
    'Salmon DNA Eyebooster'   => ['booster', '40 menit', 'Salmon DNA khusus area mata untuk membantu memperbaiki kulit tipis, kering, dan tampak lelah.'],
    'Rejuran Healer'          => ['booster', '60 menit', 'Rejuran Healer untuk membantu memperbaiki dan meregenerasi kulit yang lelah atau kurang elastis.', 'assets/images/treatment-antiaging.jpg'],
    'Rejuran HB'              => ['booster', '60 menit', 'Rejuran HB untuk membantu meremajakan kulit, menunjang elastisitas, dan tekstur yang lebih halus.', 'assets/images/treatment-antiaging.jpg'],
    'Hair Restoration Therapy' => ['hair', '45 menit', 'Terapi kulit kepala untuk membantu merawat folikel dan menunjang pertumbuhan rambut yang lebih sehat.'],
    'V-Line LipoSculpt'       => ['slimming', '30 menit', 'Tindakan injeksi untuk membantu membentuk kontur wajah bagian bawah agar tampak lebih tirus.'],
    'BodySculpt Injection'    => ['slimming', '30–45 menit', 'Injeksi untuk membantu membentuk kontur area tubuh tertentu sesuai hasil evaluasi dokter.'],
    'Uvora Face Define'       => ['slimming', '30 menit', 'Perawatan pembentukan kontur wajah untuk membantu tampilan wajah yang lebih tegas dan proporsional.'],
    'Uvora Body Define'       => ['slimming', '45 menit', 'Perawatan pembentukan kontur tubuh untuk membantu tampilan lekuk tubuh yang lebih proporsional.'],
    'Cauterisasi'             => ['cauter', '15–30 menit', 'Tindakan pengangkatan lesi kulit jinak seperti tahi lalat kecil, kutil, atau skin tag. Harga bergantung jumlah dan ukuran lesi.', 'assets/images/treatment-laser.jpg'],
    'Botox 100 UI'            => ['botox', '30 menit', 'Injeksi botulinum toxin oleh dokter untuk membantu menghaluskan garis ekspresi pada area tertentu.', 'assets/images/treatment-antiaging.jpg'],
    'Mounjaro 2,5 mg'         => ['program', 'Sesuai arahan dokter', 'Terapi dengan resep dokter yang diberikan hanya setelah konsultasi dan evaluasi kondisi kesehatan.'],
    'Mounjaro 5 mg'           => ['program', 'Sesuai arahan dokter', 'Terapi dengan resep dokter yang diberikan hanya setelah konsultasi dan evaluasi kondisi kesehatan.'],
    'Collagen Therapy'        => ['booster', '60 menit', 'Terapi kolagen untuk membantu menunjang kekenyalan dan kekencangan kulit.', 'assets/images/treatment-antiaging.jpg'],
];

// Isi halaman detail per jenis perawatan.
$treatment_types = [
    'facial' => [
        'label'    => 'Hydrafacial',
        'how_title' => 'Membersihkan, mengangkat, lalu menutrisi',
        'how_text' => 'Hydrafacial memakai teknologi vakum dan serum untuk membersihkan pori, mengangkat sel kulit mati, lalu menutrisi kulit — nyaman dan tanpa pemulihan panjang.',
        'features' => ['Membersihkan pori mendalam', 'Eksfoliasi yang lembut', 'Infus serum sesuai kebutuhan kulit', 'Tanpa waktu pemulihan panjang'],
        'suitable' => ['Kulit kusam atau tidak merata', 'Pori tampak besar atau mudah tersumbat', 'Ingin perawatan rutin yang nyaman', 'Kulit butuh hidrasi'],
        'notes'    => ['Hindari paparan matahari berlebih setelah tindakan', 'Gunakan tabir surya setiap hari', 'Beri tahu dokter bila sedang memakai obat topikal tertentu', 'Hasil dapat berbeda pada tiap orang'],
        'faq'      => [
            ['Apakah terasa sakit?', 'Umumnya nyaman. Anda mungkin merasakan sensasi tarikan ringan dari alat vakum.'],
            ['Seberapa sering perlu dilakukan?', 'Dokter akan menyarankan jadwal sesuai kondisi kulit, umumnya setiap beberapa minggu sekali.'],
        ],
    ],
    'peel' => [
        'label'    => 'Chemical Peel',
        'how_title' => 'Eksfoliasi terkontrol oleh dokter',
        'how_text' => 'Larutan peeling dioleskan secara terkontrol untuk mengangkat lapisan kulit mati sehingga kulit baru tampak lebih halus dan merata seiring waktu.',
        'features' => ['Membantu meratakan warna kulit', 'Membantu mengurangi sumbatan pori', 'Konsentrasi disesuaikan kondisi kulit', 'Dilakukan bertahap'],
        'suitable' => ['Kulit kusam dan noda ringan', 'Kulit berminyak atau mudah berjerawat', 'Tekstur kulit kasar', 'Area khusus seperti bibir atau mata'],
        'notes'    => ['Kulit dapat terasa hangat atau sedikit mengelupas', 'Wajib memakai tabir surya', 'Hindari scrub dan produk keras beberapa hari', 'Tidak disarankan saat kulit sedang iritasi berat'],
        'faq'      => [
            ['Apakah kulit akan mengelupas?', 'Bisa terjadi pengelupasan ringan dalam beberapa hari. Ini bagian dari proses pembaruan kulit.'],
            ['Apakah bisa langsung beraktivitas?', 'Umumnya bisa, dengan tetap menjaga kulit dan memakai tabir surya.'],
        ],
    ],
    'booster' => [
        'label'    => 'Skin Booster & Regenerasi',
        'how_title' => 'Menutrisi kulit dari dalam',
        'how_text' => 'Bahan aktif dimasukkan ke lapisan kulit melalui injeksi atau microneedle untuk membantu hidrasi, elastisitas, dan regenerasi kulit secara bertahap.',
        'features' => ['Membantu hidrasi lebih dalam', 'Menunjang elastisitas kulit', 'Diberikan langsung oleh dokter', 'Hasil bertahap dan alami'],
        'suitable' => ['Kulit kering, tipis, atau tampak lelah', 'Tekstur kulit kurang halus', 'Ingin kulit lebih kenyal dan sehat', 'Bekas jerawat dan pemulihan kulit'],
        'notes'    => ['Dapat muncul kemerahan atau bengkak ringan sementara', 'Hindari makeup beberapa jam setelah tindakan', 'Hindari sauna dan olahraga berat 24 jam', 'Tidak disarankan saat hamil atau menyusui'],
        'faq'      => [
            ['Apakah ada masa pemulihan?', 'Kemerahan ringan biasanya mereda dalam beberapa jam hingga hari. Dokter akan menjelaskan perawatan setelahnya.'],
            ['Berapa sesi yang dibutuhkan?', 'Tergantung kondisi kulit dan tujuan Anda. Dokter akan merekomendasikan setelah konsultasi.'],
        ],
    ],
    'hair' => [
        'label'    => 'Perawatan Rambut',
        'how_title' => 'Merawat kulit kepala dan folikel',
        'how_text' => 'Terapi dilakukan pada kulit kepala untuk membantu menutrisi folikel rambut. Dokter akan menilai penyebab kerontokan sebelum menentukan protokol yang sesuai.',
        'features' => ['Evaluasi kondisi kulit kepala', 'Protokol disesuaikan penyebab', 'Dilakukan oleh tenaga medis', 'Dilakukan bertahap'],
        'suitable' => ['Rambut rontok atau menipis', 'Kulit kepala kering atau berminyak berlebih', 'Ingin merawat kesehatan rambut', 'Telah berkonsultasi dengan dokter'],
        'notes'    => ['Hasil membutuhkan waktu dan beberapa sesi', 'Ikuti anjuran perawatan di rumah', 'Beri tahu dokter riwayat penyakit & obat', 'Hasil dapat berbeda pada tiap orang'],
        'faq'      => [
            ['Kapan hasilnya terlihat?', 'Pertumbuhan rambut membutuhkan waktu. Dokter akan menjelaskan perkiraan dan jadwal evaluasi.'],
            ['Berapa sesi yang dibutuhkan?', 'Tergantung kondisi rambut dan kulit kepala Anda; ditentukan setelah konsultasi.'],
        ],
    ],
    'slimming' => [
        'label'    => 'Slimming & Body Contouring',
        'how_title' => 'Pembentukan kontur sesuai evaluasi dokter',
        'how_text' => 'Tindakan diberikan pada area tertentu setelah dokter menilai kebutuhan dan kondisi Anda. Hasil bergantung pada kondisi tubuh serta pola hidup.',
        'features' => ['Area & dosis ditentukan dokter', 'Tindakan singkat', 'Tanpa operasi', 'Evaluasi hasil berkala'],
        'suitable' => ['Ingin membentuk kontur wajah atau tubuh', 'Area lemak yang membandel', 'Telah berkonsultasi dengan dokter', 'Menjalani pola hidup sehat'],
        'notes'    => ['Bukan pengganti diet dan olahraga', 'Dapat muncul bengkak atau memar ringan', 'Tidak disarankan saat hamil atau menyusui', 'Hasil dapat berbeda pada tiap orang'],
        'faq'      => [
            ['Apakah ada masa pemulihan?', 'Bengkak atau kemerahan ringan dapat terjadi dan biasanya mereda. Dokter akan menjelaskan perawatan setelahnya.'],
            ['Berapa sesi yang dibutuhkan?', 'Ditentukan dokter sesuai area dan target Anda.'],
        ],
    ],
    'botox' => [
        'label'    => 'Botox',
        'how_title' => 'Menghaluskan garis ekspresi',
        'how_text' => 'Botulinum toxin diinjeksikan oleh dokter pada otot tertentu untuk membantu menghaluskan garis ekspresi, dengan dosis dan titik yang disesuaikan.',
        'features' => ['Dilakukan oleh dokter', 'Dosis & titik sesuai kebutuhan', 'Tindakan singkat', 'Hasil tampak natural'],
        'suitable' => ['Garis dahi atau kerutan di sekitar mata', 'Ingin tampilan lebih rileks', 'Telah berkonsultasi dengan dokter', 'Usia dewasa dengan kondisi sehat'],
        'notes'    => ['Tidak disarankan saat hamil atau menyusui', 'Hindari memijat area suntikan beberapa jam', 'Efek muncul bertahap dan tidak permanen', 'Informasikan riwayat penyakit & alergi'],
        'faq'      => [
            ['Kapan hasilnya terlihat?', 'Efek umumnya muncul bertahap dalam beberapa hari dan bertahan beberapa bulan.'],
            ['Apakah aman?', 'Tindakan dilakukan dokter sesuai prosedur dan hasil konsultasi. Dokter akan menilai kelayakan Anda.'],
        ],
    ],
    'program' => [
        'label'    => 'Terapi dengan Resep Dokter',
        'how_title' => 'Hanya setelah konsultasi dokter',
        'how_text' => 'Terapi ini merupakan obat dengan resep. Dokter akan mengevaluasi kondisi kesehatan, riwayat penyakit, dan tujuan Anda sebelum menentukan apakah dan dosis mana yang sesuai.',
        'features' => ['Evaluasi kesehatan oleh dokter', 'Dosis sesuai arahan dokter', 'Pemantauan berkala', 'Edukasi pola hidup'],
        'suitable' => ['Telah berkonsultasi dengan dokter', 'Memenuhi indikasi medis', 'Bersedia menjalani pemantauan', 'Memberi tahu riwayat penyakit lengkap'],
        'notes'    => ['Tidak untuk semua orang', 'Tidak disarankan saat hamil atau menyusui', 'Efek samping dapat terjadi dan akan dijelaskan dokter', 'Jangan memakai tanpa pengawasan dokter'],
        'faq'      => [
            ['Apakah langsung bisa dibeli?', 'Tidak. Diperlukan konsultasi dan evaluasi dokter terlebih dahulu.'],
            ['Dosis mana yang sesuai?', 'Dosis ditentukan dokter berdasarkan kondisi dan respons tubuh Anda.'],
        ],
    ],
    'cauter' => [
        'label'    => 'Tindakan Medis',
        'how_title' => 'Pengangkatan lesi jinak yang presisi',
        'how_text' => 'Dokter memakai alat cauter untuk mengangkat lesi kulit jinak setelah area dibius lokal. Prosesnya singkat, dan jumlah serta ukuran lesi menentukan biaya akhir.',
        'features' => ['Tindakan singkat', 'Dibius lokal', 'Presisi pada lesi kecil', 'Dievaluasi dokter sebelum tindakan'],
        'suitable' => ['Tahi lalat kecil atau kutil', 'Skin tag', 'Lesi jinak yang mengganggu penampilan', 'Telah diperiksa dokter'],
        'notes'    => ['Area bekas tindakan perlu dijaga tetap bersih dan kering', 'Gunakan salep sesuai anjuran', 'Hindari mengelupas keropeng', 'Lesi yang mencurigakan perlu pemeriksaan lebih lanjut'],
        'faq'      => [
            ['Apakah terasa sakit?', 'Area dibius lokal sehingga nyeri minimal. Setelahnya mungkin terasa perih ringan.'],
            ['Berapa biayanya?', 'Mulai Rp 179.000. Biaya akhir menyesuaikan jumlah dan ukuran lesi setelah diperiksa dokter.'],
        ],
    ],
];

$treatment_steps = [
    ['Konsultasi', 'Dokter menilai kondisi kulit dan tujuan Anda.'],
    ['Persiapan', 'Kulit dibersihkan dan disiapkan sebelum tindakan.'],
    ['Tindakan', 'Perawatan dilakukan sesuai protokol dokter.'],
    ['Perawatan Pasca', 'Pemberian arahan dan produk pendukung.'],
    ['Evaluasi', 'Pemantauan hasil dan rencana sesi berikutnya.'],
];

function slugify($text)
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

// Ubah satu entri galeri menjadi ['kind' => image|video|youtube, 'src' => ..., 'thumb' => ...].
function media_entry($src)
{
    if (preg_match('~(?:youtube\.com/watch\?(?:.*&)?v=|youtu\.be/|youtube\.com/embed/)([\w-]{11})~', $src, $m)) {
        return ['kind' => 'youtube', 'src' => 'https://www.youtube.com/embed/' . $m[1] . '?rel=0', 'thumb' => 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg'];
    }
    $ext = strtolower(pathinfo(parse_url($src, PHP_URL_PATH) ?: $src, PATHINFO_EXTENSION));
    if (in_array($ext, ['mp4', 'webm', 'mov'], true)) {
        return ['kind' => 'video', 'src' => $src, 'thumb' => ''];
    }
    return ['kind' => 'image', 'src' => $src, 'thumb' => $src];
}

// Galeri: file di assets/media/<slug>/ + daftar $treatment_media[slug]; kosong => sampul saja.
function treatment_gallery($slug, $fallback_img)
{
    global $treatment_media;
    $list = [];
    $files = glob(__DIR__ . '/../assets/media/' . $slug . '/*.{jpg,jpeg,png,webp,mp4,webm,mov}', GLOB_BRACE) ?: [];
    natsort($files);
    foreach ($files as $f) {
        $list[] = 'assets/media/' . $slug . '/' . basename($f);
    }
    foreach ($treatment_media[$slug] ?? [] as $src) {
        $list[] = $src;
    }
    if (!$list) {
        $list[] = $fallback_img;
    }
    return array_map('media_entry', $list);
}

// Daftar datar seluruh perawatan beserta kategori & tier (dari harga).
$catalog = [];
foreach ($treatment_categories as $cid => $cat) {
    foreach ($cat['items'] as $item) {
        $tier = 0;
        foreach ($price_tiers as $ti => $pt) {
            if ($item['price'] < $pt['max']) { $tier = $ti; break; }
        }
        $m    = $treatment_meta[$item['name']] ?? ['facial', '-', ''];
        $slug = !empty($item['slug']) ? $item['slug'] : slugify($item['name']);
        $ty   = $treatment_types[$m[0]] ?? $treatment_types['facial'];
        $lines = function ($v) { return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $v)))); };
        $pick  = function ($v, $def) use ($lines) { $l = $lines($v); return $l ?: $def; };
        $steps = [];
        foreach ($lines($item['steps'] ?? '') as $ln) {
            $p = array_map('trim', explode('|', $ln, 2));
            $steps[] = [$p[0], $p[1] ?? ''];
        }
        // Foto dari admin (sampul + 2 foto galeri / URL video) menggantikan galeri bawaan.
        $dbImgs = array_values(array_filter([$item['img'] ?? '', $item['img2'] ?? '', $item['img3'] ?? ''], 'strlen'));
        $gallery = $dbImgs
            ? array_map('media_entry', $dbImgs)
            : treatment_gallery($slug, $m[3] ?? $treatment_image[$m[0]]);
        $cover = $gallery[0]['kind'] === 'image' ? $gallery[0]['src'] : ($gallery[0]['thumb'] ?: ($m[3] ?? $treatment_image[$m[0]]));
        foreach ($gallery as $g) {
            if ($g['kind'] === 'image') { $cover = $g['src']; break; }
        }
        $catalog[] = [
            'slug'      => $slug,
            'name'      => $item['name'],
            'price'     => $item['price'],
            'prefix'    => '',
            'cat'       => $cid,
            'tier'      => $tier,
            'cat_label' => $cat['label'],
            'type'      => $m[0],
            'dur'       => ($item['dur'] ?? '') !== '' ? $item['dur'] : $m[1],
            'desc'      => ($item['desc'] ?? '') !== '' ? $item['desc'] : $m[2],
            'how_title' => trim($item['how_title'] ?? '') !== '' ? trim($item['how_title']) : $ty['how_title'],
            'how_text'  => trim($item['how_text'] ?? '') !== '' ? trim($item['how_text']) : $ty['how_text'],
            'features'  => $pick($item['features'] ?? '', $ty['features']),
            'suitable'  => $pick($item['suitable'] ?? '', $ty['suitable']),
            'notes'     => $pick($item['notes'] ?? '', $ty['notes']),
            'steps'     => $steps ?: $treatment_steps,
            'img'       => $cover,
            'gallery'   => $gallery,
            'featured'  => !empty($item['featured']),
        ];
    }
}

function find_treatment($slug)
{
    global $catalog;
    foreach ($catalog as $t) {
        if ($t['slug'] === $slug) {
            return $t;
        }
    }
    return null;
}

function treatment_price($t)
{
    return rupiah($t['price']);
}
