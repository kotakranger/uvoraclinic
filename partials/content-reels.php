<?php
// Content Reels — portrait video carousel via embedded iframes.
// Supports Instagram (/embed/), TikTok (/embed/v2/), YouTube Shorts (/embed/).
// Data dikelola dari Admin → Dokter & Konten.
?>
<?php if (empty($content_reels)) return; ?>

<section id="konten-video" class="uv-reels-section">
  <div class="uv-container px-6 md:px-12">
    <div class="mb-14 text-center" data-reveal>
      <span class="text-xs uppercase tracking-[0.28em] text-[#ab8f2c] font-medium">Konten Terbaru</span>
      <h2 class="font-serif-display mt-4 text-4xl tracking-tight md:text-5xl">Ikuti Kami</h2>
      <p class="mt-4 text-[#6B6459] max-w-md mx-auto">Tonton tips perawatan, behind the scenes, dan transformasi kulit langsung dari tim Uvora Clinic.</p>
    </div>
  </div>

  <!-- Horizontal scroll track -->
  <div class="uv-reels-track-wrap">
    <button type="button" class="uv-reels-arrow is-prev" data-reels-prev aria-label="Sebelumnya">&#8249;</button>
    <button type="button" class="uv-reels-arrow is-next" data-reels-next aria-label="Berikutnya">&#8250;</button>
    <div class="uv-reels-track" data-reels-track>
      <?php
      $has_instagram = false;
      foreach ($content_reels as $ri => $reel):
        $url = trim($reel['embed_url']);

        // ── Detect platform ────────────────────────────────────────────────
        $is_tiktok    = preg_match('~tiktok\.com/(?:embed/v2/|@[^/]+/video/)(\d+)~', $url, $tt_m);
        $is_instagram = !$is_tiktok && preg_match('~instagram\.com/(?:p|reel|tv)/([A-Za-z0-9_\-]+)~', $url, $ig_m);
        $is_youtube   = !$is_tiktok && !$is_instagram && preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_\-]{11})~', $url, $yt_m);
      ?>
        <div class="uv-reel-card" data-reveal style="--d:<?= $ri * 0.08 ?>s">
          <?php if ($is_tiktok):
            $tt_id  = $tt_m[1];
            $tt_url = 'https://www.tiktok.com/video/' . $tt_id;
            $tt_cache = sys_get_temp_dir() . '/uv_tt_' . $tt_id . '.json';
            $tt = is_file($tt_cache) && filemtime($tt_cache) > time() - 21600
                  ? json_decode(file_get_contents($tt_cache), true) : null;
            if (!$tt) {
              $ctx = stream_context_create(['http' => ['timeout' => 6, 'header' => "User-Agent: Mozilla/5.0\r\n"]]);
              $raw = @file_get_contents('https://www.tiktok.com/oembed?url=' . urlencode($tt_url), false, $ctx);
              $tt = $raw ? json_decode($raw, true) : null;
              if ($tt) @file_put_contents($tt_cache, $raw);
            }
            $tt_thumb = $tt['thumbnail_url'] ?? '';
          ?>
            <a class="uv-reel-tt" data-tt-id="<?= e($tt_id) ?>"
               href="<?= e($tt_url) ?>" target="_blank" rel="noopener"
               aria-label="Putar video TikTok"
               style="<?= $tt_thumb ? 'background-image:url(' . e($tt_thumb) . ')' : '' ?>">
              <span class="uv-reel-play"><svg width="26" height="26" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg></span>
            </a>

          <?php elseif ($is_instagram):
            // Instagram blocks iframe embeds. Use the official blockquote+embed.js approach.
            $ig_permalink = 'https://www.instagram.com/p/' . $ig_m[1] . '/';
            $has_instagram = true;
          ?>
            <div class="uv-reel-ig-wrap">
              <blockquote class="instagram-media uv-reel-ig"
                data-instgrm-permalink="<?= e($ig_permalink) ?>"
                data-instgrm-version="14"
                style="background:#fff;border:0;border-radius:3px;box-shadow:0 0 1px 0 rgba(0,0,0,.5),0 1px 10px 0 rgba(0,0,0,.15);margin:0;max-width:100%;min-width:326px;padding:0;width:calc(100% - 2px);">
              </blockquote>
            </div>

          <?php elseif ($is_youtube):
            $yt_id  = $yt_m[1];
            $yt_src = 'https://www.youtube.com/embed/' . $yt_id . '?rel=0';
          ?>
            <iframe class="uv-reel-frame" src="<?= e($yt_src) ?>"
              frameborder="0" allow="autoplay; encrypted-media; picture-in-picture"
              allowfullscreen loading="lazy"></iframe>

          <?php else: // Generic iframe fallback ?>
            <iframe class="uv-reel-frame" src="<?= e($url) ?>"
              frameborder="0" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture"
              loading="lazy"></iframe>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Dots -->
  <div class="uv-reels-dots" data-reels-dots>
    <?php foreach ($content_reels as $ri => $reel): ?>
      <button type="button" class="uv-reels-dot<?= $ri === 0 ? ' is-active' : '' ?>" data-reels-dot="<?= $ri ?>" aria-label="Video <?= $ri + 1 ?>"></button>
    <?php endforeach; ?>
  </div>
</section>

<?php if (!empty($has_instagram)): ?>
<style>
  .uv-reel-ig-wrap {
    width: 100%; height: 100%;
    border-radius: 12px;
    overflow: hidden;        /* clip visual edges */
  }
  /* Hide scrollbars but keep scrollable so Instagram can expand */
  .uv-reel-ig-wrap .instagram-media { border-radius: 0 !important; min-width: 0 !important; }
</style>
<script>
// Called by Instagram's embed.js after it loads
window.instgrm = window.instgrm || {};
window.instgrm.Embeds = window.instgrm.Embeds || {};
// Process immediately once script loads, and again after a delay for off-screen cards
function _igProcess() {
  if (window.instgrm && window.instgrm.Embeds && typeof window.instgrm.Embeds.process === 'function') {
    window.instgrm.Embeds.process();
  }
}
window.addEventListener('load', function() {
  _igProcess();
  setTimeout(_igProcess, 1500);
  setTimeout(_igProcess, 3000);
});
</script>
<script async src="https://www.instagram.com/embed.js" onload="_igProcess()"></script>
<?php endif; ?>
<script>
(function(){
  document.querySelectorAll('.uv-reel-tt').forEach(function(a){
    a.addEventListener('click', function(e){
      e.preventDefault();
      var f = document.createElement('iframe');
      f.className = 'uv-reel-frame';
      f.src = 'https://www.tiktok.com/player/v1/' + a.dataset.ttId + '?autoplay=1&loop=1&music_info=0&description=0&rel=0';
      f.allow = 'autoplay; fullscreen; encrypted-media';
      f.setAttribute('frameborder', '0');
      a.replaceWith(f);
    });
  });
})();
(function(){
  var track = document.querySelector('[data-reels-track]');
  var dots = document.querySelectorAll('[data-reels-dot]');
  var cards = Array.prototype.slice.call(document.querySelectorAll('.uv-reel-card'));

  // Re-process any unrendered Instagram blockquotes
  function processInstagram() {
    if (window.instgrm && window.instgrm.Embeds) {
      window.instgrm.Embeds.process();
    }
  }

  // Sync dots to which card is most centered in the track
  function updateDots() {
    if (!track || !dots.length) return;
    var trackRect = track.getBoundingClientRect();
    var centerX = trackRect.left + trackRect.width / 2;
    var closest = 0, minDist = Infinity;
    cards.forEach(function(c, i) {
      var rect = c.getBoundingClientRect();
      var dist = Math.abs(rect.left + rect.width / 2 - centerX);
      if (dist < minDist) { minDist = dist; closest = i; }
    });
    dots.forEach(function(d, i) { d.classList.toggle('is-active', i === closest); });
  }

  var igTimer;
  if (track) {
    track.addEventListener('scroll', function() {
      updateDots();
      // Debounce: re-process Instagram embeds after scroll settles
      clearTimeout(igTimer);
      igTimer = setTimeout(processInstagram, 300);
    }, { passive: true });
  }
  var step = function(dir){ if (track && cards[0]) track.scrollBy({ left: dir * (cards[0].offsetWidth + 20), behavior: 'smooth' }); };
  var prev = document.querySelector('[data-reels-prev]'), next = document.querySelector('[data-reels-next]');
  if (prev) prev.addEventListener('click', function(){ step(-1); });
  if (next) next.addEventListener('click', function(){ step(1); });

  // Dot click → scroll card to center
  dots.forEach(function(dot, i) {
    dot.addEventListener('click', function() {
      var card = cards[i];
      if (!card || !track) return;
      var trackRect = track.getBoundingClientRect();
      var cardRect = card.getBoundingClientRect();
      var offset = track.scrollLeft + (cardRect.left - trackRect.left) - (trackRect.width - cardRect.width) / 2;
      track.scrollTo({ left: offset, behavior: 'smooth' });
    });
  });

  // Initial process: wait for embed.js to load then render all blockquotes
  window.addEventListener('load', function() {
    setTimeout(processInstagram, 800);
  });
})();
</script>
