<?php
/**
 * Shared "Sorotan" (highlights) grid: 3 columns of photos and/or YouTube videos.
 * Used by portfolio/detail and implentasi_partner/index.
 *
 * Expects (set by the including view):
 *   $highlightItems        array of ['type' => 'image'|'video', 'image' => file, 'video_url' => url, 'caption' => text]
 *   $highlightHeading      HTML for the section heading
 *   $highlightSectionClass extra classes for the <section> (optional)
 * Images live in assets/img/portfolio/. Renders nothing when there are no valid items.
 */
$highlightCards = [];
foreach ($highlightItems ?? [] as $h) {
    $isVideo = ($h['type'] ?? 'image') === 'video';
    $ytId = $isVideo ? $this->youtubeId($h['video_url'] ?? '') : null;
    if ($isVideo && !$ytId) continue;
    if (!$isVideo && empty($h['image'])) continue;
    $highlightCards[] = ['video' => $isVideo, 'ytId' => $ytId, 'image' => $h['image'] ?? '', 'caption' => trim($h['caption'] ?? '')];
}
if (!$highlightCards) return;

?>
<?php if (!defined('HIGHLIGHT_ASSETS_PRINTED')): define('HIGHLIGHT_ASSETS_PRINTED', true); // styles + video modal once per page ?>
<style>
    .highlights-section {
        background:
            radial-gradient(circle at 12% 18%, rgba(13, 110, 253, 0.10), transparent 30%),
            linear-gradient(180deg, #f8fafc 0%, #eef6ff 100%);
    }
    .highlight-card {
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        height: 300px;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        cursor: pointer;
    }
    .highlight-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 45px rgba(0,0,0,0.15) !important;
    }
    .highlight-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .highlight-card:hover img {
        transform: scale(1.1);
    }
    .highlight-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 40%, transparent 100%);
        display: flex;
        align-items: flex-end;
        padding: 25px;
        color: white;
        opacity: 0.9;
        transition: all 0.4s ease;
    }
    .highlight-card:hover .highlight-overlay {
        opacity: 1;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
    }
    .highlight-overlay p {
        transform: translateY(5px);
        transition: transform 0.4s ease;
    }
    .highlight-card:hover .highlight-overlay p {
        transform: translateY(0);
    }
    .highlight-play {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255, 0, 0, 0.9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        z-index: 2;
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        transition: transform 0.3s ease;
        pointer-events: none;
    }
    .highlight-video:hover .highlight-play {
        transform: translate(-50%, -50%) scale(1.12);
    }
</style>

<div class="modal fade" id="highlightVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-0 mb-2">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden bg-black">
                    <iframe id="highlightVideoIframe" src="" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('highlightVideoModal');
    const iframe = document.getElementById('highlightVideoIframe');
    if (!modal) return;
    modal.addEventListener('show.bs.modal', function (e) {
        const id = e.relatedTarget.getAttribute('data-video-id');
        iframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
    });
    modal.addEventListener('hidden.bs.modal', function () { iframe.src = ''; });
});
</script>
<?php endif; ?>

<section class="py-5 highlights-section <?= htmlspecialchars($highlightSectionClass ?? '') ?>">
    <div class="container">
        <?= $highlightHeading ?? '' ?>
        <div class="row g-4">
            <?php foreach ($highlightCards as $card): ?>
            <div class="col-lg-4 col-md-6">
                <?php if ($card['video']): ?>
                <div class="highlight-card shadow-sm highlight-video" role="button" data-bs-toggle="modal" data-bs-target="#highlightVideoModal" data-video-id="<?= htmlspecialchars($card['ytId']) ?>" aria-label="Putar video">
                    <img loading="lazy" decoding="async" src="https://img.youtube.com/vi/<?= htmlspecialchars($card['ytId']) ?>/hqdefault.jpg" alt="<?= htmlspecialchars($card['caption'] ?: 'Video') ?>" loading="lazy">
                    <div class="highlight-play"><i class="fas fa-play"></i></div>
                <?php else: ?>
                <div class="highlight-card shadow-sm">
                    <img loading="lazy" decoding="async" src="<?= ASSETS_URL ?>img/portfolio/<?= htmlspecialchars($card['image']) ?>" alt="<?= htmlspecialchars($card['caption'] ?: 'Sorotan') ?>" loading="lazy">
                <?php endif; ?>
                    <?php if ($card['caption'] !== ''): ?>
                    <div class="highlight-overlay">
                        <p class="small mb-0"><?= htmlspecialchars($card['caption']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
