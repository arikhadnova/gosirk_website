<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/portfolio" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block">PORTFOLIO / EDIT</span>
            <h1 class="fw-bold mb-0">Edit Proyek</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/portfolio_update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $portfolio->id; ?>">
    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <!-- Main Content: Indonesian -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-file-alt me-2"></i>Konten Proyek</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Basic Info -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Judul Proyek</label>
                        <input type="text" name="title_id" class="form-control form-control-lg" value="<?= $portfolio->title_id; ?>" <?= FormRules::attrs('portfolio', 'title_id', 'update') ?>>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Sub-judul</label>
                        <input type="text" name="subtitle_id" class="form-control" value="<?= $portfolio->subtitle_id; ?>" <?= FormRules::attrs('portfolio', 'subtitle_id', 'update') ?>>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Deskripsi Ringkas (Card Link)</label>
                        <textarea name="description_id" class="form-control" rows="3" <?= FormRules::attrs('portfolio', 'description_id', 'update') ?>><?= $portfolio->description_id; ?></textarea>
                    </div>

                    <hr class="my-4 border-dashed">

                    <!-- NEW: Media Section moved here -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Icon FontAwesome</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-icons text-muted"></i></span>
                                <input type="text" name="icon_name" class="form-control border-start-0" value="<?= $portfolio->icon_name; ?>" id="iconInput" <?= FormRules::attrs('portfolio', 'icon_name', 'update') ?>>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div id="iconPreview" class="bg-light rounded p-2 text-center" style="width: 40px;"><i class="<?= $portfolio->icon_name ?: 'fas fa-question text-muted'; ?>"></i></div>
                                <small class="text-muted extra-small">Cari di <a href="https://fontawesome.com/v5/search?m=free" target="_blank">FontAwesome 5</a></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Gambar Cover Baru</label>
                            <input type="file" name="cover_image" class="form-control form-control-sm">
                            <?php if ($portfolio->cover_image) : ?>
                                <div class="mt-2 d-flex align-items-center gap-2 p-2 border rounded bg-light">
                                    <img src="<?= ASSETS_URL; ?>img/portfolio/<?= $portfolio->cover_image; ?>" style="max-height: 40px; border-radius: 4px;" alt="Current Cover">
                                    <span class="extra-small text-muted">Cover saat ini</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Logo Proyek Terkait (Partner/Klien)</label>
                        <div id="project-logos-container">
                            <?php 
                            $logos = json_decode($portfolio->project_logos ?: '[]', true);
                            foreach($logos as $logo): 
                            ?>
                            <div class="project-logo-row mb-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <img src="<?= ASSETS_URL; ?>img/portfolio/<?= $logo; ?>" style="max-height: 20px;">
                                        <input type="hidden" name="existing_project_logos[]" value="<?= $logo; ?>">
                                    </span>
                                    <input type="text" class="form-control form-control-sm" value="<?= $logo; ?>" readonly>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-logo"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <div class="project-logo-row mb-2 new-logo">
                                <div class="input-group">
                                    <input type="file" name="project_logos[]" class="form-control form-control-sm">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-logo"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-logo"><i class="fas fa-plus me-1"></i> Tambah Logo Baru</button>
                    </div>

                </div>
            </div>

            <!-- Card 2: Detailed Content -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-align-left me-2"></i>Detail & Dokumentasi Proyek</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark"><i class="fas fa-info-circle me-1 text-primary"></i> 1. TENTANG PROYEK (Isi Detail)</label>
                        <textarea name="detail_content_id" id="editor_detail" class="form-control" rows="10" <?= FormRules::attrs('portfolio', 'detail_content_id', 'update') ?>><?= $portfolio->detail_content_id; ?></textarea>
                    </div>

                    <?php
                    $highlightItems = json_decode($portfolio->highlights ?: '[]', true) ?: [];
                    // Legacy single video field -> show as a video highlight so it can be edited/removed
                    $legacyVideo = $portfolio->video_url ?? '';
                    if ($legacyVideo && $this->youtubeId($legacyVideo) && !in_array($legacyVideo, array_column($highlightItems, 'video_url'), true)) {
                        array_unshift($highlightItems, ['type' => 'video', 'video_url' => $legacyVideo, 'caption' => '']);
                    }
                    if (empty($highlightItems)) $highlightItems = [['type' => 'image']];
                    require __DIR__ . '/portfolio_highlights_field.php';
                    ?>
                </div>
            </div>

        </div>

        <!-- Sidebar Options Column -->
        <div class="col-lg-4">
            <!-- Metadata & Settings -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Metadata & Kategori</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tipe Partner (Badge)</label>
                        <select name="partner_type" class="form-select rounded-3">
                            <option value="GOVERNMENT" <?= $portfolio->partner_type == 'GOVERNMENT' ? 'selected' : ''; ?>>Government</option>
                            <option value="COMMUNITY" <?= $portfolio->partner_type == 'COMMUNITY' ? 'selected' : ''; ?>>Community</option>
                            <option value="EDUCATION" <?= $portfolio->partner_type == 'EDUCATION' ? 'selected' : ''; ?>>Education/Academic</option>
                            <option value="CORPORATE" <?= $portfolio->partner_type == 'CORPORATE' ? 'selected' : ''; ?>>Corporate/CSR</option>
                            <option value="NGO" <?= $portfolio->partner_type == 'NGO' ? 'selected' : ''; ?>>NGO/Foundation</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Client Name</label>
                        <input type="text" name="client_name" class="form-control" value="<?= $portfolio->client_name; ?>" <?= FormRules::attrs('portfolio', 'client_name', 'update') ?>>
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Pengaturan Tampilan & Kategori Halaman</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <!-- Home -->
                        <div class="display-option-group">
                            <label class="display-checkbox-card d-flex align-items-center mb-2">
                                <input type="checkbox" name="show_home" id="check_home" class="form-check-input me-3" <?= $portfolio->show_home ? 'checked' : ''; ?>>
                                <div>
                                    <div class="fw-bold">Halaman Utama (Home)</div>
                                    <small class="text-muted">Tampil di section Portfolio & Partnership.</small>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Partnership -->
                        <div class="display-option-group">
                            <label class="display-checkbox-card d-flex align-items-center mb-2">
                                <input type="checkbox" name="show_partnership" id="check_partnership" class="form-check-input me-3" <?= $portfolio->show_partnership ? 'checked' : ''; ?>>
                                <div>
                                    <div class="fw-bold">Halaman Partnership</div>
                                    <small class="text-muted">Tampil di kategori Collaboration Model.</small>
                                </div>
                            </label>
                            <div id="cat_partnership" class="ms-4 ps-3 border-start mb-3">
                                <label class="form-label extra-small fw-bold text-muted">Kategori di Partnership</label>
                                <select name="partnership_category" class="form-select form-select-sm">
                                    <option value="">-- Pilih Tipe Partnership --</option>
                                    <option value="community" <?= $portfolio->partnership_category == 'community' ? 'selected' : ''; ?>>Community Partnership</option>
                                    <option value="academic" <?= $portfolio->partnership_category == 'academic' ? 'selected' : ''; ?>>Academic Partnership</option>
                                    <option value="program" <?= $portfolio->partnership_category == 'program' ? 'selected' : ''; ?>>Program Partnership</option>
                                </select>
                            </div>
                        </div>

                        <!-- GI -->
                        <div class="display-option-group">
                            <label class="display-checkbox-card d-flex align-items-center mb-2">
                                <input type="checkbox" name="show_gi" id="check_gi" class="form-check-input me-3" <?= $portfolio->show_gi ? 'checked' : ''; ?>>
                                <div>
                                    <div class="fw-bold">GoSirk Institute (GI)</div>
                                    <small class="text-muted">Tampil di portal edukasi & knowledge.</small>
                                </div>
                            </label>
                            <div id="cat_gi" class="ms-4 ps-3 border-start">
                                <label class="form-label extra-small fw-bold text-muted">Section di GI</label>
                                <select name="gi_category" class="form-select form-select-sm">
                                    <option value="">-- Pilih Section GI --</option>
                                    <option value="daerah" <?= $portfolio->gi_category == 'daerah' ? 'selected' : ''; ?>>1. Peningkatan Kapasitas Daerah</option>
                                    <option value="desa" <?= $portfolio->gi_category == 'desa' ? 'selected' : ''; ?>>2. Pendampingan Desa</option>
                                    <option value="kampanye" <?= $portfolio->gi_category == 'kampanye' ? 'selected' : ''; ?>>3. Kampanye Perubahan Perilaku</option>
                                    <option value="modul" <?= $portfolio->gi_category == 'modul' ? 'selected' : ''; ?>>4. Penyusunan Modul & Toolkit</option>
                                    <option value="webinar" <?= $portfolio->gi_category == 'webinar' ? 'selected' : ''; ?>>5. Webinar & E-learning</option>
                                    <option value="akademik" <?= $portfolio->gi_category == 'akademik' ? 'selected' : ''; ?>>6. Academic Partnership (GI)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <button type="submit" class="btn btn-warning w-100 fw-bold py-3 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN
                    </button>
                    <a href="<?= BASE_URL; ?>admin/portfolio" class="btn btn-link text-white text-decoration-none mt-3 d-block small">Batalkan</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CKEditor Initialization
        ClassicEditor
            .create(document.querySelector('#editor_detail'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });


    // Dynamic Lists Handler
    const setupDynamicList = (containerId, addButtonId, rowClass, removeBtnClass, template) => {
        const container = document.getElementById(containerId);
        const addButton = document.getElementById(addButtonId);

        addButton.addEventListener('click', () => {
            const div = document.createElement('div');
            div.innerHTML = template.trim();
            container.appendChild(div.firstChild);
        });

        container.addEventListener('click', (e) => {
            if (e.target.classList.contains(removeBtnClass) || e.target.closest('.' + removeBtnClass)) {
                const row = e.target.closest('.' + rowClass);
                if (row) row.remove();
            }
        });
    };

    // Project Logo Template
    const logoTemplate = `
        <div class="project-logo-row mb-2">
            <div class="input-group">
                <input type="file" name="project_logos[]" class="form-control form-control-sm">
                <button type="button" class="btn btn-sm btn-outline-danger remove-logo"><i class="fas fa-times"></i></button>
            </div>
        </div>`;
    setupDynamicList('project-logos-container', 'add-logo', 'project-logo-row', 'remove-logo', logoTemplate);

    // Conditional Category Display
    const toggles = [
        { check: 'check_partnership', target: 'cat_partnership' },
        { check: 'check_gi', target: 'cat_gi' }
    ];

    toggles.forEach(t => {
        const checkbox = document.getElementById(t.check);
        const section = document.getElementById(t.target);
        
        const updateVisibility = () => {
            section.style.display = checkbox.checked ? 'block' : 'none';
        };

        checkbox.addEventListener('change', updateVisibility);
        updateVisibility(); // Initial state
    });
});
</script>
