<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/partners" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block">PARTNER / TAMBAH</span>
            <h1 class="fw-bold mb-0">Tambah Partner Baru</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/partners_store" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Informasi Partner</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Nama Instansi / Perusahaan</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: PT. Sumber Alfaria Trijaya" <?= FormRules::attrs('partner', 'name', 'store') ?>>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Kategori / Tipe</label>
                        <select name="type" class="form-select" <?= FormRules::attrs('partner', 'type', 'store') ?>>
                            <option value="GOVERNMENT">Government</option>
                            <option value="COMMUNITY">Community</option>
                            <option value="EDUCATION">Education</option>
                            <option value="CORPORATE">Corporate</option>
                            <option value="NGO">NGO / Foundation</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Group / Penempatan</label>
                        <select name="category" class="form-select" <?= FormRules::attrs('partner', 'category', 'store') ?>>
                            <option value="contribution">Our Contribution & Partner</option>
                            <option value="network">Our Network</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Logo Partner</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 text-center">
                        <div id="logoPreview" class="border rounded-4 mb-3 d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                            <span class="text-muted">Pratinjau Logo</span>
                        </div>
                        <input type="file" name="logo" class="form-control" accept="image/*" id="logoInput" <?= FormRules::attrs('partner', 'logo', 'store') ?>>
                        <div class="form-text small mt-2">Gunakan format PNG transparan jika memungkinkan. Max 1MB.</div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Tambah Partner
                    </button>
                    <a href="<?= BASE_URL; ?>admin/partners" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small text-center d-block">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('logoInput').onchange = evt => {
    const [file] = logoInput.files
    if (file) {
        logoPreview.innerHTML = `<img src="${URL.createObjectURL(file)}" style="max-width: 100%; max-height: 100%;">`;
    }
}
</script>
