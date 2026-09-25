<?php
$settings = $data['settings'];
$siteTitle = $settings['site_title'] ?? 'Go Circular Solutions Indonesia';
$siteDesc = $settings['site_description'] ?? '';
$host = preg_replace('#^https?://#', '', rtrim(BASE_URL, '/'));
$seoPages = !empty($data['only']) ? array_intersect_key($data['pages'], [$data['only'] => true]) : $data['pages'];
?>
<style>
    .seo-item { padding: 1rem 1.25rem; }
    .seo-item + .seo-item { border-top: 1px solid #f1f3f7; }
    .seo-url { font-size: .75rem; color: #5f6b7a; }
    .seo-title { color: #1a0dab; font-size: 1.02rem; line-height: 1.3; }
    .seo-desc { color: #4d5156; font-size: .85rem; line-height: 1.45; }
    .seo-default { color: #9aa3b2; }
</style>

<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / KONTEN / SEO</span>
    <h1 class="fw-bold mb-0">SEO Halaman</h1>
    <p class="text-muted small mb-0">Tampilan setiap halaman di hasil pencarian Google dan saat link dibagikan. Klik Edit untuk mengubah.</p>
</div>

<form action="<?= BASE_URL; ?>admin/update_seo" method="POST" style="max-width: 860px;">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <?php foreach ($seoPages as $page => $label) :
                $t = $settings["seo.$page.title"] ?? '';
                $d = $settings["seo.$page.description"] ?? '';
                $path = $page === 'home' ? '' : $page;
            ?>
                <div class="seo-item">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-grow-1 min-w-0">
                            <div class="seo-url text-truncate"><?= htmlspecialchars($host . '/' . $path) ?> &middot; <span class="fw-semibold text-dark"><?= htmlspecialchars($label) ?></span></div>
                            <div class="seo-title text-truncate seo-preview-title <?= $t === '' ? 'seo-default' : '' ?>"><?= htmlspecialchars($t !== '' ? $t : $siteTitle) ?></div>
                            <div class="seo-desc seo-preview-desc <?= $d === '' ? 'seo-default' : '' ?>"><?= htmlspecialchars($d !== '' ? $d : $siteDesc) ?></div>
                        </div>
                        <?php if (count($seoPages) > 1) : ?><button type="button" class="btn btn-light btn-action btn-sm flex-shrink-0 seo-edit"><i class="fas fa-pen"></i> Edit</button><?php endif; ?>
                    </div>
                    <div class="seo-fields mt-3 <?= count($seoPages) > 1 ? 'd-none' : '' ?>">
                        <label class="form-label small text-muted mb-1">Judul <span class="seo-count"></span></label>
                        <input type="text" name="seo[<?= $page ?>][title]" class="form-control form-control-sm mb-2 seo-field" data-limit="60" maxlength="100" data-default="<?= htmlspecialchars($siteTitle) ?>"
                               value="<?= htmlspecialchars($t) ?>" placeholder="<?= htmlspecialchars($siteTitle) ?>">
                        <label class="form-label small text-muted mb-1">Deskripsi <span class="seo-count"></span></label>
                        <textarea name="seo[<?= $page ?>][description]" rows="2" class="form-control form-control-sm seo-field" data-limit="160" maxlength="300" data-default="<?= htmlspecialchars($siteDesc) ?>"
                                  placeholder="<?= htmlspecialchars($siteDesc) ?>"><?= htmlspecialchars($d) ?></textarea>
                        <small class="text-muted d-block mt-1">Kosongkan untuk memakai judul &amp; deskripsi global (Pengaturan › Situs).</small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="position-sticky bottom-0 py-3" style="z-index: 5;">
        <div class="card border-0 shadow rounded-4">
            <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between gap-3">
                <small class="text-muted">Halaman detail (artikel, portfolio, layanan GI) otomatis memakai judulnya sendiri.</small>
                <button type="submit" class="btn btn-primary btn-action px-4"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.seo-edit').forEach((btn) => btn.addEventListener('click', () => {
        const fields = btn.closest('.seo-item').querySelector('.seo-fields');
        fields.classList.toggle('d-none');
        btn.innerHTML = fields.classList.contains('d-none') ? '<i class="fas fa-pen"></i> Edit' : '<i class="fas fa-chevron-up"></i> Tutup';
        if (!fields.classList.contains('d-none')) fields.querySelector('input').focus();
    }));

    // Live preview + length hint
    document.querySelectorAll('.seo-field').forEach((el) => {
        const item = el.closest('.seo-item');
        const counter = el.previousElementSibling.querySelector('.seo-count');
        const preview = item.querySelector(el.tagName === 'INPUT' ? '.seo-preview-title' : '.seo-preview-desc');
        const update = () => {
            const n = el.value.length, limit = +el.dataset.limit;
            counter.textContent = n ? `· ${n}/${limit}` : '';
            counter.classList.toggle('text-warning', n > limit);
            preview.textContent = el.value || el.dataset.default;
            preview.classList.toggle('seo-default', !el.value);
        };
        el.addEventListener('input', update);
        update();
    });
});
</script>
