<?php
// Shared "Sorotan" field for portfolio create/edit. Expects $highlightItems (array of highlight items).
$highlightItems = $highlightItems ?? [];
$highlightFieldLabel = $highlightFieldLabel ?? '2. SOROTAN (Highlights)';

$renderHighlightRow = function ($h = []) {
    $type = $h['type'] ?? 'image';
    $image = $type === 'image' ? ($h['image'] ?? '') : '';
    $video = $type === 'video' ? ($h['video_url'] ?? '') : '';
    ?>
    <div class="p-3 bg-light rounded-3 mb-2 highlight-row">
        <div class="row g-2 align-items-center">
            <div class="col-md-2">
                <select name="highlight_types[]" class="form-select form-select-sm highlight-type">
                    <option value="image" <?= $type === 'image' ? 'selected' : ''; ?>>Foto</option>
                    <option value="video" <?= $type === 'video' ? 'selected' : ''; ?>>Video</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="highlight-image-input d-flex align-items-center gap-2">
                    <?php if ($image) : ?>
                        <img src="<?= ASSETS_URL; ?>img/portfolio/<?= htmlspecialchars($image); ?>" class="rounded border" style="height: 32px;" alt="">
                    <?php endif; ?>
                    <input type="hidden" name="highlight_existing_imgs[]" value="<?= htmlspecialchars($image); ?>">
                    <input type="file" name="highlight_imgs[]" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="highlight-video-input">
                    <input type="url" name="highlight_videos[]" class="form-control form-control-sm" value="<?= htmlspecialchars($video); ?>" placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>
            <div class="col-md-4">
                <input type="text" name="highlight_captions[]" class="form-control form-control-sm" value="<?= htmlspecialchars($h['caption'] ?? ''); ?>" placeholder="Keterangan (opsional)...">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-link text-danger remove-highlight p-0"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    </div>
    <?php
};
?>
<div class="mb-0">
    <label class="form-label small fw-bold text-dark mb-1"><i class="fas fa-images me-1 text-primary"></i> <?= htmlspecialchars($highlightFieldLabel) ?></label>
    <small class="text-muted extra-small d-block mb-3">Tampil sebagai grid 3 kolom. Setiap item bisa berupa foto atau link video YouTube.</small>
    <div id="highlights-container">
        <?php foreach ($highlightItems as $h) { $renderHighlightRow($h); } ?>
    </div>
    <template id="highlight-row-template"><?php $renderHighlightRow(); ?></template>
    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-highlight"><i class="fas fa-plus me-1"></i> Tambah Sorotan</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('highlights-container');
    const template = document.getElementById('highlight-row-template');

    const syncRow = (row) => {
        const isVideo = row.querySelector('.highlight-type').value === 'video';
        row.querySelector('.highlight-image-input').classList.toggle('d-none', isVideo);
        row.querySelector('.highlight-video-input').classList.toggle('d-none', !isVideo);
    };

    container.querySelectorAll('.highlight-row').forEach(syncRow);

    document.getElementById('add-highlight').addEventListener('click', () => {
        const row = template.content.firstElementChild.cloneNode(true);
        container.appendChild(row);
        syncRow(row);
    });

    container.addEventListener('change', (e) => {
        if (e.target.classList.contains('highlight-type')) {
            syncRow(e.target.closest('.highlight-row'));
        }
    });

    container.addEventListener('click', (e) => {
        const btn = e.target.closest('.remove-highlight');
        if (btn) btn.closest('.highlight-row').remove();
    });
});
</script>
