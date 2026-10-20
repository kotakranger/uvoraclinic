<?php
/**
 * includes/uploader.php
 * Image upload helper: validates, resizes (GD) and stores as WebP/JPEG.
 * Also renders the shared "upload OR url" admin field.
 */

const UV_UPLOAD_MAX_BYTES = 5 * 1024 * 1024;   // max size of the original upload (5 MB)
const UV_UPLOAD_MAX_EDGE  = 1200;              // longest side after resize (px)
const UV_UPLOAD_QUALITY   = 82;

/**
 * Resolve the final image path for a form field.
 *
 * @param string $field   base field name (e.g. "img"); file input is "{$field}_file"
 * @param string $subdir  folder under assets/uploads (doctors|layanan|promos)
 * @param string $current value of the URL/path text input
 * @return string path (relative to site root) or URL; throws RuntimeException on bad upload
 */
function handle_image_field(string $field, string $subdir, string $current = ''): string
{
    $current = trim($current);
    $key = $field . '_file';
    if (empty($_FILES[$key]) || ($_FILES[$key]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $current;
    }
    return save_uploaded_image($_FILES[$key], $subdir);
}

function save_uploaded_image(array $f, string $subdir): string
{
    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE) {
        throw new RuntimeException('Ukuran foto terlalu besar (maks. 5 MB).');
    }
    if ($f['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($f['tmp_name'])) {
        throw new RuntimeException('Upload foto gagal, coba lagi.');
    }
    if ($f['size'] > UV_UPLOAD_MAX_BYTES) {
        throw new RuntimeException('Ukuran foto terlalu besar (maks. 5 MB).');
    }
    $info = @getimagesize($f['tmp_name']);
    if (!$info) {
        throw new RuntimeException('File bukan gambar yang valid.');
    }
    $type = $info[2];
    switch ($type) {
        case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($f['tmp_name']); break;
        case IMAGETYPE_PNG:  $src = @imagecreatefrompng($f['tmp_name']);  break;
        case IMAGETYPE_WEBP: $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($f['tmp_name']) : false; break;
        default: throw new RuntimeException('Format harus JPG, PNG, atau WebP.');
    }
    if (!$src) {
        throw new RuntimeException('Gagal membaca gambar.');
    }

    // Fix phone-camera rotation
    if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($f['tmp_name']);
        $o = $exif['Orientation'] ?? 1;
        $angle = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
        if ($angle) {
            $rot = imagerotate($src, $angle, 0);
            if ($rot) { imagedestroy($src); $src = $rot; }
        }
    }

    $w = imagesx($src); $h = imagesy($src);
    $scale = min(1, UV_UPLOAD_MAX_EDGE / max($w, $h));
    $nw = max(1, (int)round($w * $scale));
    $nh = max(1, (int)round($h * $scale));
    $dst = imagecreatetruecolor($nw, $nh);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 255, 255, 255, 127));
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    imagedestroy($src);

    $dir = dirname(__DIR__) . '/assets/uploads/' . preg_replace('/[^a-z0-9_-]/i', '', $subdir);
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new RuntimeException('Folder upload tidak bisa dibuat.');
    }
    $base = date('Ymd-His') . '-' . bin2hex(random_bytes(4));
    if (function_exists('imagewebp')) {
        $file = $base . '.webp';
        $ok = imagewebp($dst, "$dir/$file", UV_UPLOAD_QUALITY);
    } else {
        $file = $base . '.jpg';
        $bg = imagecreatetruecolor($nw, $nh);
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagecopy($bg, $dst, 0, 0, 0, 0, $nw, $nh);
        $ok = imagejpeg($bg, "$dir/$file", UV_UPLOAD_QUALITY);
        imagedestroy($bg);
    }
    imagedestroy($dst);
    if (!$ok) {
        throw new RuntimeException('Gagal menyimpan gambar.');
    }
    return 'assets/uploads/' . basename($dir) . '/' . $file;
}

/**
 * Render the shared image field: file upload + URL/path input + live preview.
 */
function image_field(string $name, string $label = 'Foto', string $id = '', string $value = ''): void
{
    static $scriptDone = false;
    $idAttr = $id !== '' ? ' id="' . htmlspecialchars($id) . '"' : '';
    $maxMb  = UV_UPLOAD_MAX_BYTES / 1048576;
    ?>
<div class="form-group uv-imgfield">
  <label class="lbl"><?= htmlspecialchars($label) ?></label>
  <div class="uv-imgfield-row">
    <?php $pv = $value === '' ? '' : (preg_match('~^(https?:|data:|blob:|/)~', $value) ? $value : '../' . $value); ?>
    <img class="uv-imgfield-prev" alt="" src="<?= htmlspecialchars($pv) ?>" style="display:<?= $pv === '' ? 'none' : 'block' ?>">
    <div class="uv-imgfield-inputs">
      <input type="file" name="<?= $name ?>_file" accept="image/jpeg,image/png,image/webp" data-max="<?= UV_UPLOAD_MAX_BYTES ?>" onchange="uvImgFile(this)">
      <input type="text" name="<?= $name ?>"<?= $idAttr ?> value="<?= htmlspecialchars($value) ?>" placeholder="…atau tempel URL gambar / path (assets/images/…)" oninput="uvImgSync(this)">
      <p class="hint">Upload JPG/PNG/WebP maks. <?= $maxMb ?> MB — otomatis diperkecil &amp; dikompres. Bisa juga isi URL manual.</p>
    </div>
  </div>
</div>
<?php if (!$scriptDone): $scriptDone = true; ?>
<style>
  .uv-imgfield-row{display:flex;gap:12px;align-items:flex-start}
  .uv-imgfield-prev{width:72px;height:72px;object-fit:cover;border-radius:10px;border:1px solid #ddd;flex:none;background:#f4f4f4}
  .uv-imgfield-inputs{flex:1;min-width:0;display:flex;flex-direction:column;gap:6px}
</style>
<script>
function uvImgSync(input){
  var wrap = input.closest('.uv-imgfield'); if(!wrap) return;
  var prev = wrap.querySelector('.uv-imgfield-prev');
  var v = (input.value || '').trim();
  if(!v){ prev.style.display='none'; return; }
  prev.src = /^(https?:|data:|blob:|\/)/.test(v) ? v : '../' + v;
  prev.style.display = 'block';
}
function uvImgFile(file){
  var wrap = file.closest('.uv-imgfield');
  var f = file.files[0]; if(!f) return;
  if(f.size > parseInt(file.dataset.max,10)){
    alert('Ukuran foto terlalu besar (maks. ' + Math.round(file.dataset.max/1048576) + ' MB).');
    file.value=''; return;
  }
  var prev = wrap.querySelector('.uv-imgfield-prev');
  prev.src = URL.createObjectURL(f); prev.style.display='block';
}
</script>
<?php endif;
}
