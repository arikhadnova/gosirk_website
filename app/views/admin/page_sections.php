<?php
$sections = $data['sections'] ?? [];
$pages = $data['pages'] ?? [];
?>

<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / PAGE SECTIONS</span>
    <h1 class="fw-bold mb-0">Page Sections</h1>
    <p class="text-muted small mb-0">Kelola konten section About/Tentang di halaman utama layanan dan ekosistem.</p>
</div>

<div class="row">
    <div class="col-lg-11 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-0">
                <ul class="nav nav-tabs border-0 px-4 pt-3" role="tablist">
                    <?php $first = true; foreach ($sections as $page => $section): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $first ? 'active' : ''; ?> fw-bold border-0 bg-transparent py-3 px-4"
                                id="<?= $page; ?>-section-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#section-<?= $page; ?>"
                                type="button"
                                role="tab">
                            <?= htmlspecialchars($pages[$page] ?? $page); ?>
                        </button>
                    </li>
                    <?php $first = false; endforeach; ?>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content">
                    <?php $first = true; foreach ($sections as $page => $section): ?>
                    <?php
                        $image = $section->image ?? '';
                        $imageUrl = '';
                        $paragraphCounts = [
                            'about' => 3,
                            'gi' => 1,
                            'ggc' => 2,
                            'go_ngompos_project' => 2,
                            'konsultan' => 1,
                            'partner' => 2
                        ];
                        $paragraphCount = $paragraphCounts[$page] ?? 2;
                        if ($image) {
                            $imageUrl = filter_var($image, FILTER_VALIDATE_URL) ? $image : ASSETS_URL . 'img/' . $image;
                        }
                    ?>
                    <div class="tab-pane fade <?= $first ? 'show active' : ''; ?>" id="section-<?= $page; ?>" role="tabpanel">
                        <form action="<?= BASE_URL; ?>admin/update_page_section" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="page_name" value="<?= htmlspecialchars($page); ?>">
                            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($image, ENT_QUOTES); ?>">

                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="p-4 rounded-4 border mb-4">
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1">Konten About / Tentang</h6>
                                                <p class="text-muted extra-small mb-0">Versi English akan dibuat otomatis saat disimpan.</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-dark">Badge</label>
                                                <input type="text" name="badge_id" class="form-control" value="<?= htmlspecialchars($section->badge_id ?? '', ENT_QUOTES); ?>" <?= FormRules::attrs('page_section', 'badge_id', 'update') ?>>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-dark">Title</label>
                                                <textarea name="title_id" class="form-control" rows="2" <?= FormRules::attrs('page_section', 'title_id', 'update') ?>><?= htmlspecialchars($section->title_id ?? ''); ?></textarea>
                                                <div class="text-muted extra-small mt-1">HTML sederhana seperti span/bold boleh dipakai.</div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-dark">Paragraf 1</label>
                                                <textarea name="content_id" class="form-control" rows="5" <?= FormRules::attrs('page_section', 'content_id', 'update') ?>><?= htmlspecialchars($section->content_id ?? ''); ?></textarea>
                                            </div>

                                            <?php if ($paragraphCount >= 2): ?>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-dark">Paragraf 2</label>
                                                <textarea name="content_2_id" class="form-control" rows="4"><?= htmlspecialchars($section->content_2_id ?? ''); ?></textarea>
                                            </div>
                                            <?php endif; ?>

                                            <?php if ($paragraphCount >= 3): ?>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-dark">Paragraf 3</label>
                                                <textarea name="content_3_id" class="form-control" rows="4"><?= htmlspecialchars($section->content_3_id ?? ''); ?></textarea>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="p-4 bg-white rounded-4 border shadow-sm sticky-lg-top" style="top: 90px;">
                                        <h6 class="fw-bold mb-3">Preview & Gambar</h6>

                                        <div class="section-preview rounded-4 overflow-hidden bg-light mb-3">
                                            <?php if ($imageUrl): ?>
                                                <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES); ?>" alt="Section image" class="w-100 h-100 object-fit-cover">
                                            <?php else: ?>
                                                <div class="d-flex align-items-center justify-content-center h-100 text-muted small">Section ini tidak memakai gambar.</div>
                                            <?php endif; ?>
                                        </div>

                                        <label class="form-label small fw-bold text-dark">Upload Gambar</label>
                                        <input type="file" name="image" class="form-control" accept="image/*,.svg">
                                        <div class="text-muted extra-small mt-2">Rekomendasi: 900x700px, JPG/PNG/WebP. Kosongkan jika tidak ingin mengganti.</div>

                                        <div class="border-top mt-4 pt-4">
                                            <div class="text-muted extra-small text-uppercase fw-bold mb-2">Preview teks</div>
                                            <div class="small fw-bold text-primary mb-1"><?= htmlspecialchars($section->badge_id ?? ''); ?></div>
                                            <div class="fw-bold mb-2"><?= $section->title_id ?? ''; ?></div>
                                            <p class="text-muted small mb-2"><?= $section->content_id ?? ''; ?></p>
                                            <?php if ($paragraphCount >= 2 && !empty($section->content_2_id)): ?>
                                                <p class="text-muted small mb-0"><?= $section->content_2_id; ?></p>
                                            <?php endif; ?>
                                            <?php if ($paragraphCount >= 3 && !empty($section->content_3_id)): ?>
                                                <p class="text-muted small mb-0"><?= $section->content_3_id; ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 fw-bold mt-4">
                                            <i class="fas fa-save me-2"></i> Save Section
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php $first = false; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        transition: all 0.2s;
        border-bottom: 3px solid transparent !important;
    }
    .nav-tabs .nav-link.active {
        color: var(--primary-color) !important;
        border-bottom: 3px solid var(--primary-color) !important;
    }
    .extra-small {
        font-size: 0.75rem;
    }
    .section-preview {
        height: 220px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (!window.location.hash) return;
    const targetPane = document.querySelector(window.location.hash);
    if (!targetPane) return;
    const trigger = document.querySelector('[data-bs-target="' + window.location.hash + '"]');
    if (trigger && window.bootstrap) {
        bootstrap.Tab.getOrCreateInstance(trigger).show();
    }
});
</script>
