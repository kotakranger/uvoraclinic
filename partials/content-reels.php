<?php
// Content Reels — portrait video carousel via embedded iframes.
// Supports Instagram (/embed/), TikTok (/embed/v2/), YouTube Shorts (/embed/).
// Data dikelola dari backoffice — saat ini dummy dengan 1 video IG.
?>

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
      <?php foreach ($content_reels as $ri => $reel): ?>
        <div class="uv-reel-card" data-reveal style="--d:<?= $ri * 0.08 ?>s">
          <?php if (preg_match('~tiktok\.com/embed/v2/(\d+)~', $reel['embed_url'], $tm)):
            $tt_url = 'https://www.tiktok.com/@indaha.l/video/' . $tm[1];
            $tt_cache = sys_get_temp_dir() . '/uv_tt_' . $tm[1] . '.json';
            $tt = is_file($tt_cache) && filemtime($tt_cache) > time() - 21600 ? json_decode(file_get_contents($tt_cache), true) : null;
            if (!$tt) {
              $ctx = stream_context_create(['http' => ['timeout' => 6, 'header' => "User-Agent: Mozilla/5.0\r\n"]]);
              $raw = @file_get_contents('https://www.tiktok.com/oembed?url=' . urlencode($tt_url), false, $ctx);
              $tt = $raw ? json_decode($raw, true) : null;
              if ($tt) @file_put_contents($tt_cache, $raw);
            }
            $tt_thumb = $tt['thumbnail_url'] ?? '';
          ?>
            <a class="uv-reel-tt" data-tt-id="<?= e($tm[1]) ?>" href="<?= e($tt_url) ?>" target="_blank" rel="noopener" aria-label="Putar video TikTok" style="<?= $tt_thumb ? 'background-image:url(' . e($tt_thumb) . ')' : '' ?>">
              <span class="uv-reel-play"><svg width="26" height="26" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg></span>
            </a>
          <?php else: ?>
          <iframe
            class="uv-reel-frame"
            src="<?= e($reel['embed_url']) ?>"
            frameborder="0"
            scrolling="no"
            allowtransparency="true"
            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture"
            loading="eager"
          ></iframe>
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

<?php if (!empty($has_tiktok)): ?><script async src="https://www.tiktok.com/embed.js"></script><?php endif; ?>
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

  if (track) {
    track.addEventListener('scroll', updateDots, { passive: true });
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
})();
</script>
