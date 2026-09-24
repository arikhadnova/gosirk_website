<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge mb-2 d-inline-block">DASHBOARD / COLLABORATION / ADD</span>
        <h1 class="fw-bold mb-0">Tambah Dokumen Kolaborasi</h1>
        <p class="text-muted small mb-0">Unggah Executive Summary atau Company Profile baru.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/collaboration" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="form-container">
            <form action="<?= BASE_URL; ?>admin/collaboration_store" method="POST" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Judul Dokumen</label>
                        <input type="text" name="title_id" class="form-control" placeholder="Contoh: Roadmap Keberlanjutan 2026" required>
                    </div>
                    <div class="col-md-12 mt-0">
                        <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan dibuat otomatis.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipe Dokumen</label>
                        <select name="type" class="form-select" required>
                            <option value="executive_summary">Executive Summary</option>
                            <option value="company_profile">Company Profile</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active (Tampil)</option>
                            <option value="inactive">Inactive (Sembunyikan)</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">File Dokumen (PDF)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf" required>
                        <div class="form-text mt-2 small text-muted">
                            <i class="fas fa-info-circle me-1"></i> File ini akan disimpan di folder aman dan tidak dapat diakses langsung oleh publik.
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">
                            <i class="fas fa-save me-2"></i> Simpan Dokumen
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
