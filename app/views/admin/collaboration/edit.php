<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge mb-2 d-inline-block">DASHBOARD / COLLABORATION / EDIT</span>
        <h1 class="fw-bold mb-0">Edit Dokumen Kolaborasi</h1>
        <p class="text-muted small mb-0">Ubah detail atau ganti file dokumen rahasia.</p>
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
            <form action="<?= BASE_URL; ?>admin/collaboration_update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['doc']->id; ?>">
                <input type="hidden" name="old_file" value="<?= $data['doc']->file_path; ?>">
                
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Judul Dokumen</label>
                        <input type="text" name="title_id" class="form-control" value="<?= $data['doc']->title_id; ?>" required>
                    </div>
                    <div class="col-md-12 mt-0">
                        <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan diperbarui otomatis saat disimpan.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipe Dokumen</label>
                        <select name="type" class="form-select" required>
                            <option value="executive_summary" <?= $data['doc']->type == 'executive_summary' ? 'selected' : ''; ?>>Executive Summary</option>
                            <option value="company_profile" <?= $data['doc']->type == 'company_profile' ? 'selected' : ''; ?>>Company Profile</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?= $data['doc']->status == 'active' ? 'selected' : ''; ?>>Active (Tampil)</option>
                            <option value="inactive" <?= $data['doc']->status == 'inactive' ? 'selected' : ''; ?>>Inactive (Sembunyikan)</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">File Dokumen (PDF)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf">
                        <div class="form-text mt-2 small text-muted">
                            <i class="fas fa-file-pdf me-1"></i> File saat ini: <span class="text-primary"><?= $data['doc']->file_path; ?></span><br>
                            <small>Kosongkan jika tidak ingin mengganti file.</small>
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">
                            <i class="fas fa-save me-2"></i> Perbarui Dokumen
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
