<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/gi_services" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block text-uppercase">DASHBOARD / CAPACITY BUILDING / EDIT</span>
            <h1 class="fw-bold mb-0">Edit Layanan GI</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/gi_services_update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $service->id; ?>">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-file-alt me-2"></i>Informasi Layanan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Judul Layanan</label>
                        <input type="text" name="title_id" class="form-control form-control-lg" placeholder="Contoh: Training dan Workshop" value="<?= $service->title_id; ?>" <?= FormRules::attrs('gi_service', 'title_id', 'update') ?>>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Deskripsi Singkat</label>
                        <textarea name="description_id" class="form-control" rows="3" placeholder="Ringkasan layanan untuk kartu di halaman utama..." <?= FormRules::attrs('gi_service', 'description_id', 'update') ?>><?= $service->description_id; ?></textarea>
                    </div>

                    <hr class="my-4 border-dashed">



                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark mb-3"><i class="fas fa-list-ol me-1 text-primary"></i> POIN PROGRAM / MATERI (Dynamic)</label>
                        <div id="program-points-container">
                            <?php 
                            $points = json_decode($service->program_points_id ?: '[]', true);
                            if (empty($points)) $points = [];
                            foreach($points as $p): 
                            ?>
                                <div class="p-3 bg-light rounded-3 mb-2 point-row">
                                    <div class="mb-2">
                                        <input type="text" name="point_titles[]" class="form-control form-control-sm fw-bold border-0 bg-transparent" value="<?= $p['title'] ?? ''; ?>" placeholder="Judul Materi / Poin">
                                    </div>
                                    <div class="mb-0">
                                        <textarea name="point_descs[]" class="form-control form-control-sm border-0 bg-transparent" rows="2" placeholder="Deskripsi materi..."><?= $p['desc'] ?? ''; ?></textarea>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm btn-link text-danger remove-point p-0 text-decoration-none extra-small">Hapus</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-point"><i class="fas fa-plus me-1"></i> Tambah Poin Program</button>
                        <input type="hidden" name="program_points_id" id="points_json">
                    </div>

                    <hr class="my-4 border-dashed">

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark"><i class="fas fa-align-left me-1 text-primary"></i> KONTEN DETAIL (Rich Text)</label>
                        <textarea name="detail_content_id" id="editor_detail" class="form-control" rows="15" <?= FormRules::attrs('gi_service', 'detail_content_id', 'update') ?>><?= $service->detail_content_id; ?></textarea>
                    </div>

                    <hr class="my-4 border-dashed">

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark mb-3"><i class="fas fa-images me-1 text-primary"></i> SOROTAN FOTO (Highlights Gallery)</label>
                        <div id="highlights-container" class="row g-2">
                             <?php 
                             $highlights = json_decode($service->highlights ?: '[]', true);
                             foreach($highlights as $h): 
                             ?>
                             <div class="col-md-6 highlight-row mb-2">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <div class="d-flex gap-3 align-items-center mb-2">
                                        <img src="<?= ASSETS_URL; ?>img/gi/<?= $h['image']; ?>" class="rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                                        <input type="hidden" name="existing_highlight_imgs[]" value="<?= $h['image']; ?>">
                                        <div class="flex-grow-1">
                                            <input type="text" name="existing_highlight_captions[]" class="form-control form-control-sm" value="<?= $h['caption'] ?? ''; ?>" placeholder="Keterangan foto...">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-link text-danger remove-highlight p-0"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                             <div class="col-md-6 highlight-row new-highlight mb-2">
                                <div class="p-3 bg-white border border-dashed rounded-3 h-100">
                                    <div class="mb-2">
                                        <input type="file" name="highlight_imgs[]" class="form-control form-control-sm">
                                    </div>
                                    <input type="text" name="highlight_captions[]" class="form-control form-control-sm" placeholder="Keterangan foto baru...">
                                    <div class="text-end mt-1">
                                        <button type="button" class="btn btn-sm btn-link text-danger remove-highlight p-0"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-highlight"><i class="fas fa-plus me-1"></i> Tambah Foto Sorotan</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-dark">Pengaturan Layanan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Kategori</label>
                        <select name="category" class="form-select" <?= FormRules::attrs('gi_service', 'category', 'update') ?>>
                            <option value="training" <?= $service->category == 'training' ? 'selected' : ''; ?>>Training</option>
                            <option value="publikasi-riset" <?= $service->category == 'publikasi-riset' ? 'selected' : ''; ?>>Publikasi dan Riset</option>
                            <option value="fasilitasi-knowledge" <?= $service->category == 'fasilitasi-knowledge' ? 'selected' : ''; ?>>Fasilitasi & Knowledge Exchange</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Gambar Utama (Cover)</label>
                        <?php if ($service->image) : ?>
                            <div class="mb-2">
                                <img src="<?= ASSETS_URL; ?>img/gi/<?= $service->image; ?>" class="img-fluid rounded-3 border">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted extra-small d-block mt-2">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Urutan Prioritas</label>
                        <input type="number" name="order_priority" class="form-control" value="<?= $service->order_priority; ?>" <?= FormRules::attrs('gi_service', 'order_priority', 'update') ?>>
                    </div>
                    <hr class="my-3 border-dashed">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Lokasi Layanan</label>
                        <input type="text" name="location_id" class="form-control" placeholder="Contoh: Online / Offline (Disesuaikan)" value="<?= $service->location_id ?: 'Online / Offline (Disesuaikan)'; ?>" <?= FormRules::attrs('gi_service', 'location_id', 'update') ?>>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Sifat Layanan</label>
                        <input type="text" name="service_type_id" class="form-control" placeholder="Contoh: Profesional & Adaptif" value="<?= $service->service_type_id ?: 'Profesional & Adaptif'; ?>" <?= FormRules::attrs('gi_service', 'service_type_id', 'update') ?>>
                    </div>
                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> PERBARUI LAYANAN
                    </button>
                    <a href="<?= BASE_URL; ?>admin/gi_services" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small text-center d-block">Batal dan Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // CKEditor Initialization
    if (typeof ClassicEditor !== 'undefined') {
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
    }



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

    // Templates


    const pointTemplate = `
        <div class="p-3 bg-light rounded-3 mb-2 point-row">
            <div class="mb-2">
                <input type="text" name="point_titles[]" class="form-control form-control-sm fw-bold border-0 bg-transparent" placeholder="Judul Materi / Poin">
            </div>
            <div class="mb-0">
                <textarea name="point_descs[]" class="form-control form-control-sm border-0 bg-transparent" rows="2" placeholder="Deskripsi materi..."></textarea>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-sm btn-link text-danger remove-point p-0 text-decoration-none extra-small">Hapus</button>
            </div>
        </div>`;

    const highlightTemplate = `
        <div class="col-md-6 highlight-row new-highlight mb-2">
            <div class="p-3 bg-white border border-dashed rounded-3 h-100">
                <div class="mb-2">
                    <input type="file" name="highlight_imgs[]" class="form-control form-control-sm">
                </div>
                <input type="text" name="highlight_captions[]" class="form-control form-control-sm" placeholder="Keterangan foto baru...">
                <div class="text-end mt-1">
                    <button type="button" class="btn btn-sm btn-link text-danger remove-highlight p-0"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>`;

    setupDynamicList('program-points-container', 'add-point', 'point-row', 'remove-point', pointTemplate);
    setupDynamicList('highlights-container', 'add-highlight', 'highlight-row', 'remove-highlight', highlightTemplate);

    // Form Submission: Package JSON & Comma Separated Strings
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Build Program Points JSON
        const points = [];
        document.querySelectorAll('.point-row').forEach(row => {
            const title = row.querySelector('input[name="point_titles[]"]').value;
            const desc = row.querySelector('textarea[name="point_descs[]"]').value;
            if (title || desc) points.push({ title, desc });
        });
        document.getElementById('points_json').value = JSON.stringify(points);
    });
});
</script>

