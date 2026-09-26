<?php
// Same overlay as .hero-full::after in assets/css/hero.css, so the preview matches the website
if (!defined('HERO_PREVIEW_OVERLAY')) define('HERO_PREVIEW_OVERLAY', 'linear-gradient(to top, rgba(7, 17, 33, .92) 0%, rgba(7, 17, 33, .62) 35%, rgba(7, 17, 33, .28) 70%, rgba(7, 17, 33, .18) 100%)');
?>
<?php
$heroes = $data['heroes'];
$pages = [
    'home' => 'Home',
    'partner' => 'Implementasi Partner',
    'konsultan' => 'Konsultan',
    'gi' => 'GoSirk Institute',
    'ggc' => 'GoSirk Green Community',
    'go_ngompos_project' => 'Go Ngompos Project'
];
if (!empty($data['only'])) $pages = array_intersect_key($pages, [$data['only'] => true]); // opened from a page hub
?>

<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / BANNER UTAMA</span>
    <h1 class="fw-bold mb-0">Banner Utama (Hero)</h1>
    <p class="text-muted small mb-0">Judul, subjudul, dan gambar besar di bagian paling atas halaman.</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-0 <?= count($pages) > 1 ? '' : 'd-none' ?>">
                <ul class="nav nav-tabs border-0 px-4 pt-3" id="heroTabs" role="tablist">
                    <?php $first = true; foreach ($pages as $key => $name): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $first ? 'active' : ''; ?> fw-bold border-0 bg-transparent py-3 px-4 position-relative" 
                                id="<?= $key; ?>-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#tab-<?= $key; ?>" 
                                type="button" 
                                role="tab">
                            <?= $name; ?>
                            <?php if ($first) $first = false; ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="heroTabsContent">
                    <?php $first = true; foreach ($pages as $key => $name): 
                        $hero = $heroes[$key] ?? null;
                    ?>
                    <div class="tab-pane fade <?= $first ? 'show active' : ''; ?>" id="tab-<?= $key; ?>" role="tabpanel">
                        <?php if ($hero): ?>
                        <form action="<?= BASE_URL; ?>admin/update_hero" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="page_name" value="<?= $key; ?>">
                            
                            <div class="row mb-5">
                                <div class="col-12">
                                    <div class="p-4 bg-light rounded-4 border text-center mb-4">
                                        <div class="small fw-bold text-muted mb-3 text-uppercase tracking-wider">Pratinjau Hero</div>
                                        <div class="hero-preview-container position-relative rounded-3 overflow-hidden shadow-sm mx-auto" style="max-width: 800px; height: 350px;">
                                            <?php
                                                $decodedImages = json_decode($hero->image ?? '', true);
                                                $heroImages = is_array($decodedImages) ? $decodedImages : [$hero->image ?? ''];
                                                $heroImages = array_values(array_filter(array_map('trim', $heroImages), function ($image) {
                                                    return $image && (filter_var($image, FILTER_VALIDATE_URL) || file_exists(__DIR__ . '/../../../assets/img/' . $image));
                                                }));
                                                $previewImages = array_map(function ($image) {
                                                    if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
                                                        return ASSETS_URL . 'img/' . $image;
                                                    }
                                                    return $image;
                                                }, $heroImages);
                                                $imageUrl = $previewImages[0] ?? '';
                                            ?>
                                            <!-- Same look as the website: dark at the bottom, text aligned to the bottom -->
                                            <div class="preview-bg" data-preview-bg style="background: <?= HERO_PREVIEW_OVERLAY ?>, url('<?= $imageUrl; ?>') center/cover no-repeat; width: 100%; height: 100%;"></div>
                                            <div class="preview-content position-absolute bottom-0 start-0 w-100 px-4 pb-4 text-white text-center">
                                                <h3 class="fw-bold mb-1 <?= in_array($key, ['partner', 'konsultan'], true) ? 'text-uppercase' : '' ?>"><?= $hero->title_id; ?></h3>
                                                <p class="small mb-1" style="max-width: 600px; margin: 0 auto; opacity: .88;"><?= $hero->subtitle_id; ?></p>
                                                <?php if($hero->tag_id): ?>
                                                    <div class="small fw-semibold" style="color: #ff9f43;"><?= $hero->tag_id; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if (true): ?>
                                            <div class="hero-slider-manager text-start mt-4" data-max-slides="5">
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                                                    <div>
                                                        <label class="form-label small fw-bold text-dark mb-1">Gambar Slider</label>
                                                        <div class="text-muted extra-small">Rekomendasi: 1920x900px, format JPG/PNG/WebP.</div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <label class="small fw-bold text-muted mb-0" for="<?= $key; ?>-transition">Transisi</label>
                                                        <select name="hero_transition" id="<?= $key; ?>-transition" class="form-select form-select-sm rounded-pill" style="width: 130px;">
                                                            <?php $homeTransition = $data['hero_transitions'][$key] ?? 'slide'; ?>
                                                            <option value="slide" <?= $homeTransition === 'slide' ? 'selected' : ''; ?>>Slide</option>
                                                            <option value="fade" <?= $homeTransition === 'fade' ? 'selected' : ''; ?>>Fade</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="hero-slide-slots d-grid gap-2">
                                                    <?php foreach (array_slice($heroImages, 0, 5) as $index => $heroImage): 
                                                        $previewImage = $previewImages[$index] ?? '';
                                                    ?>
                                                        <div class="hero-slide-slot" data-slide-slot>
                                                            <div class="hero-slide-thumb-wrap position-relative flex-shrink-0">
                                                                <button type="button" class="hero-slide-thumb-btn border-0 p-0 rounded-3 overflow-hidden" data-preview-image="<?= htmlspecialchars($previewImage, ENT_QUOTES); ?>">
                                                                    <img src="<?= htmlspecialchars($previewImage, ENT_QUOTES); ?>" alt="Slide <?= $index + 1; ?>" class="hero-slide-thumb w-100 h-100 object-fit-cover">
                                                                </button>
                                                                <button type="button" class="hero-slide-remove" aria-label="Hapus slide">X</button>
                                                            </div>
                                                            <input type="hidden" name="hero_existing_images[]" value="<?= htmlspecialchars($heroImage, ENT_QUOTES); ?>">
                                                            <input type="file" name="hero_slide_images[]" class="hero-slide-input d-none" accept="image/*">
                                                        </div>
                                                    <?php endforeach; ?>

                                                    <?php if (count($heroImages) < 5): ?>
                                                        <div class="hero-slide-slot border-dashed" data-slide-slot data-empty-slot="true">
                                                            <div class="hero-slide-thumb-wrap position-relative flex-shrink-0">
                                                                <button type="button" class="hero-slide-thumb-btn is-empty border-0 p-0 rounded-3 overflow-hidden bg-light text-muted">
                                                                    <i class="fas fa-plus"></i>
                                                                    <img src="" alt="" class="hero-slide-thumb w-100 h-100 object-fit-cover d-none">
                                                                </button>
                                                                <button type="button" class="hero-slide-remove d-none" aria-label="Hapus slide">X</button>
                                                            </div>
                                                            <input type="hidden" name="hero_existing_images[]" value="">
                                                            <input type="file" name="hero_slide_images[]" class="hero-slide-input d-none" accept="image/*">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="text-muted extra-small mt-2">Maksimal 5 gambar. Maks. 10 MB/file, otomatis dikompres.</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Single Content -->
                                <div class="col-12 px-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <h6 class="fw-bold mb-0">Konten Hero</h6>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark">Tag / Hashtag</label>
                                        <input type="text" name="tag_id" class="form-control" value="<?= htmlspecialchars($hero->tag_id); ?>" <?= FormRules::attrs('hero', 'tag_id', 'update') ?>>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark">Judul Hero (boleh HTML)</label>
                                        <textarea name="title_id" class="form-control" rows="2" <?= FormRules::attrs('hero', 'title_id', 'update') ?>><?= htmlspecialchars($hero->title_id); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark">Hero Subtitle</label>
                                        <textarea name="subtitle_id" class="form-control" rows="3" <?= FormRules::attrs('hero', 'subtitle_id', 'update') ?>><?= htmlspecialchars($hero->subtitle_id); ?></textarea>
                                    </div>

                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan diperbarui otomatis saat disimpan.</small>
                                    </div>
                                </div>

                                <?php if (in_array($key, ['ggc', 'go_ngompos_project'])): ?>
                                <?php
                                    $logoFile = $data['hero_logos'][$key] ?? ($key === 'ggc' ? 'logo-ggc.png' : 'logo-go-ngompos.svg');
                                    $logoUrl = $logoFile && filter_var($logoFile, FILTER_VALIDATE_URL) ? $logoFile : ASSETS_URL . 'img/' . $logoFile;
                                ?>
                                <div class="col-12 px-4">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 bg-light rounded-3">
                                        <div>
                                            <label class="form-label small fw-bold text-dark mb-1">Hero Logo</label>
                                            <div class="text-muted extra-small">Klik logo untuk mengganti. Rekomendasi: SVG/PNG transparan, lebar 500px.</div>
                                        </div>
                                        <div class="hero-logo-upload" data-logo-upload>
                                            <button type="button" class="hero-logo-thumb border-0 rounded-3 bg-white p-3 shadow-sm">
                                                <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES); ?>" alt="<?= htmlspecialchars($name, ENT_QUOTES); ?> Logo" class="hero-logo-preview">
                                            </button>
                                            <input type="file" name="hero_logo" class="hero-logo-input d-none" accept="image/*,.svg">
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="col-12 mt-5 text-end pt-3 border-top">
                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> Save Changes for <?= $name; ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php if ($first) $first = false; endforeach; ?>
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
    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
        background: rgba(var(--primary-rgb), 0.05);
    }
    .extra-small {
        font-size: 0.75rem;
    }
    .preview-content h3 {
        font-size: 1.75rem;
        line-height: 1.15;
        text-shadow: 0 2px 10px rgba(0, 0, 0, .35);
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .hero-slide-slots {
        display: flex !important;
        flex-wrap: wrap;
        gap: 14px !important;
    }
    .hero-slide-slot {
        width: 122px;
        padding: 6px;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        background: #fff;
    }
    .hero-slide-thumb-btn {
        cursor: pointer;
        width: 108px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .hero-slide-thumb-btn.is-empty {
        font-size: 1.25rem;
    }
    .hero-slide-remove {
        position: absolute;
        top: -7px;
        right: -7px;
        z-index: 2;
        width: 22px;
        height: 22px;
        border: 0;
        border-radius: 999px;
        background: #dc3545;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        line-height: 22px;
        padding: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.18);
    }
    .hero-logo-thumb {
        width: 150px;
        height: 92px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .hero-logo-preview {
        max-width: 100%;
        max-height: 64px;
        object-fit: contain;
    }
</style>

<script>
    document.querySelectorAll('.hero-slider-manager').forEach(function(manager) {
        const form = manager.closest('form');
        const slots = manager.querySelector('.hero-slide-slots');
        const maxSlides = parseInt(manager.dataset.maxSlides || '5', 10);
        const previewBg = form.querySelector('[data-preview-bg]');

        function updatePreview(imageUrl) {
            if (!imageUrl || !previewBg) return;
            previewBg.style.background = "<?= HERO_PREVIEW_OVERLAY ?>, url('" + imageUrl + "') center/cover no-repeat";
        }

        function renumberSlots() {
            return;
        }

        function filledSlotsCount() {
            return Array.from(slots.querySelectorAll('[data-slide-slot]')).filter(function(slot) {
                return !slot.dataset.emptySlot || slot.querySelector('.hero-slide-input').files.length > 0;
            }).length;
        }

        function addEmptySlot() {
            if (slots.querySelector('[data-empty-slot="true"]') || slots.querySelectorAll('[data-slide-slot]').length >= maxSlides) return;

            const slot = document.createElement('div');
            slot.className = 'hero-slide-slot border-dashed';
            slot.dataset.slideSlot = '';
            slot.dataset.emptySlot = 'true';
            slot.innerHTML = `
                <div class="hero-slide-thumb-wrap position-relative flex-shrink-0">
                    <button type="button" class="hero-slide-thumb-btn is-empty border-0 p-0 rounded-3 overflow-hidden bg-light text-muted">
                        <i class="fas fa-plus"></i>
                        <img src="" alt="" class="hero-slide-thumb w-100 h-100 object-fit-cover d-none">
                    </button>
                    <button type="button" class="hero-slide-remove d-none" aria-label="Hapus slide">X</button>
                </div>
                <input type="hidden" name="hero_existing_images[]" value="">
                <input type="file" name="hero_slide_images[]" class="hero-slide-input d-none" accept="image/*">
            `;
            slots.appendChild(slot);
        }

        slots.addEventListener('click', function(event) {
            const removeButton = event.target.closest('.hero-slide-remove');
            if (removeButton) {
                const slot = removeButton.closest('[data-slide-slot]');
                slot.remove();
                addEmptySlot();
                renumberSlots();
                const firstPreview = slots.querySelector('.hero-slide-thumb-btn[data-preview-image]');
                if (firstPreview) {
                    firstPreview.click();
                }
                return;
            }

            const thumbButton = event.target.closest('.hero-slide-thumb-btn');
            if (thumbButton && thumbButton.dataset.previewImage) {
                slots.querySelectorAll('.hero-slide-thumb-btn').forEach(function(button) {
                    button.classList.remove('active');
                });
                thumbButton.classList.add('active');
                updatePreview(thumbButton.dataset.previewImage);
            }

            if (thumbButton) {
                const slot = thumbButton.closest('[data-slide-slot]');
                const input = slot.querySelector('.hero-slide-input');
                if (input) input.click();
            }
        });

        slots.addEventListener('change', function(event) {
            const input = event.target.closest('.hero-slide-input');
            if (!input || !input.files.length) return;

            const slot = input.closest('[data-slide-slot]');
            const file = input.files[0];
            const imageUrl = URL.createObjectURL(file);
            const thumbButton = slot.querySelector('.hero-slide-thumb-btn');
            const thumbImage = slot.querySelector('.hero-slide-thumb');
            const plusIcon = slot.querySelector('.fa-plus');
            const removeButton = slot.querySelector('.hero-slide-remove');

            slot.removeAttribute('data-empty-slot');
            slot.classList.remove('border-dashed');
            thumbButton.classList.remove('is-empty', 'bg-light', 'text-muted');
            thumbButton.dataset.previewImage = imageUrl;
            thumbImage.src = imageUrl;
            thumbImage.classList.remove('d-none');
            if (plusIcon) plusIcon.classList.add('d-none');
            removeButton.classList.remove('d-none');

            updatePreview(imageUrl);
            addEmptySlot();
            renumberSlots();

            if (filledSlotsCount() > maxSlides) {
                alert('Maksimal ' + maxSlides + ' gambar untuk hero slider.');
                input.value = '';
                slot.remove();
                addEmptySlot();
                renumberSlots();
            }
        });

        const firstPreview = slots.querySelector('.hero-slide-thumb-btn[data-preview-image]');
        if (firstPreview) {
            firstPreview.classList.add('active');
        }
    });

    document.querySelectorAll('[data-single-hero-manager]').forEach(function(manager) {
        const form = manager.closest('form');
        const slot = manager.querySelector('[data-single-hero-slot]');
        const thumbButton = manager.querySelector('.hero-slide-thumb-btn');
        const thumbImage = manager.querySelector('.hero-slide-thumb');
        const plusIcon = manager.querySelector('.fa-plus');
        const removeButton = manager.querySelector('.hero-slide-remove');
        const input = manager.querySelector('.single-hero-input');
        const removeInput = manager.querySelector('[data-remove-hero-image]');
        const previewBg = form.querySelector('[data-preview-bg]');

        function updatePreview(imageUrl) {
            if (!previewBg) return;
            previewBg.style.background = imageUrl
                ? "linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('" + imageUrl + "') center/cover no-repeat"
                : "linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6))";
        }

        thumbButton.addEventListener('click', function() {
            input.click();
        });

        input.addEventListener('change', function() {
            if (!input.files.length) return;

            const imageUrl = URL.createObjectURL(input.files[0]);
            slot.classList.remove('border-dashed');
            thumbButton.classList.remove('is-empty', 'bg-light', 'text-muted');
            thumbButton.dataset.previewImage = imageUrl;
            thumbImage.src = imageUrl;
            thumbImage.classList.remove('d-none');
            if (plusIcon) plusIcon.classList.add('d-none');
            removeButton.classList.remove('d-none');
            removeInput.value = '0';
            updatePreview(imageUrl);
        });

        removeButton.addEventListener('click', function(event) {
            event.stopPropagation();
            input.value = '';
            removeInput.value = '1';
            slot.classList.add('border-dashed');
            thumbButton.classList.add('is-empty', 'bg-light', 'text-muted');
            thumbButton.dataset.previewImage = '';
            thumbImage.src = '';
            thumbImage.classList.add('d-none');
            if (plusIcon) plusIcon.classList.remove('d-none');
            removeButton.classList.add('d-none');
            updatePreview('');
        });
    });

    document.querySelectorAll('[data-logo-upload]').forEach(function(manager) {
        const button = manager.querySelector('.hero-logo-thumb');
        const input = manager.querySelector('.hero-logo-input');
        const preview = manager.querySelector('.hero-logo-preview');

        button.addEventListener('click', function() {
            input.click();
        });

        input.addEventListener('change', function() {
            if (!input.files.length) return;
            preview.src = URL.createObjectURL(input.files[0]);
        });
    });
</script>
