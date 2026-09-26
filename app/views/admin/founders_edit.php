<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/founders" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block">FOUNDER / EDIT</span>
            <h1 class="fw-bold mb-0">Edit Founder</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/founders_update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $founder->id; ?>">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Informasi Founder</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="<?= $founder->name; ?>" <?= FormRules::attrs('founder', 'name', 'update') ?>>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="role_id" class="form-control" value="<?= $founder->role_id; ?>" <?= FormRules::attrs('founder', 'role_id', 'update') ?>><?= EnField::render('role', $founder->role_id ?? '', $founder->role_en ?? '', 'text', 1) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Kutipan</label>
                        <textarea name="quote_id" class="form-control" rows="3" <?= FormRules::attrs('founder', 'quote_id', 'update') ?>><?= $founder->quote_id; ?></textarea><?= EnField::render('quote', $founder->quote_id ?? '', $founder->quote_en ?? '', 'textarea', 3) ?>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6">
                            <label class="form-label">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="form-control" value="<?= $founder->linkedin_url; ?>" <?= FormRules::attrs('founder', 'linkedin_url', 'update') ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="display_order" class="form-control" value="<?= $founder->display_order; ?>" <?= FormRules::attrs('founder', 'display_order', 'update') ?>>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Foto Founder</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 text-center">
                        <div id="imagePreview" class="border rounded-circle mb-3 d-flex align-items-center justify-content-center bg-light mx-auto" style="width: 150px; height: 150px; overflow: hidden;">
                            <?php if ($founder->image) : ?>
                                <img src="<?= ASSETS_URL; ?>img/<?= $founder->image; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else : ?>
                                <span class="text-muted">Pratinjau</span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput" <?= FormRules::attrs('founder', 'image', 'update') ?>>
                        <div class="form-text small mt-2">Biarkan kosong jika tidak ingin mengubah foto.</div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="<?= BASE_URL; ?>admin/founders" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small text-center d-block">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('imageInput').onchange = evt => {
    const [file] = imageInput.files
    if (file) {
        imagePreview.innerHTML = `<img src="${URL.createObjectURL(file)}" style="width: 100%; height: 100%; object-fit: cover;">`;
    }
}
</script>
