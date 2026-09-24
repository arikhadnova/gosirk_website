<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/publications" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block">PUBLICATIONS / EDIT</span>
            <h1 class="fw-bold mb-0">Edit Publikasi</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/publications_update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $publication->id; ?>">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-1">Konten Publikasi</h5>
                    <p class="text-muted extra-small mb-0">Versi English akan diperbarui otomatis saat disimpan.</p>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Judul Publikasi</label>
                        <input type="text" name="title_id" class="form-control" value="<?= $publication->title_id; ?>" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description_id" class="form-control" rows="5"><?= $publication->description_id; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">File & Metadata</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Tipe Publikasi</label>
                        <select name="type" class="form-select">
                            <option value="gosirk" <?= $publication->type == 'gosirk' ? 'selected' : ''; ?>>GoSirk Publication</option>
                            <option value="reference" <?= $publication->type == 'reference' ? 'selected' : ''; ?>>Reference / Legal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Update File (PDF)</label>
                        <input type="file" name="file_path" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah file</small>
                        <?php if ($publication->file_path) : ?>
                            <small class="text-muted d-block mt-2"><i class="fas fa-file-pdf me-1"></i> Current file: <?= $publication->file_path; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Update Preview (PDF)</label>
                        <input type="file" name="preview_path" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah preview</small>
                        <?php if ($publication->preview_path) : ?>
                            <small class="text-muted d-block mt-2"><i class="fas fa-file-pdf me-1"></i> Current preview: <?= $publication->preview_path; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Link Eksternal</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-link text-muted"></i></span>
                            <input type="url" name="external_link" class="form-control border-start-0 ps-0" value="<?= $publication->external_link ?>" placeholder="https://example.com/external-source">
                        </div>
                        <small class="text-muted extra-small">Masukkan link ke sumber luar jika ada</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Ganti Thumbnail - Opsional</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <?php if ($publication->thumbnail) : ?>
                            <div class="mt-2 text-center p-2 border rounded-3 bg-light">
                                <img src="<?= ASSETS_URL; ?>img/publications/<?= $publication->thumbnail; ?>" style="max-height: 100px; border-radius: 8px;" alt="Current Thumbnail">
                            </div>
                        <?php endif; ?>
                    </div>
                    <hr class="my-3">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Status Berbayar</label>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_paid" id="isPaidToggle" <?= $publication->is_paid ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="isPaidToggle">Publikasi Berbayar</label>
                        </div>
                        <div id="priceInputWrapper" style="display: <?= $publication->is_paid ? 'block' : 'none'; ?>;">
                            <label class="form-label small fw-bold text-dark">Harga (RP)</label>
                            <input type="number" name="price" class="form-control" placeholder="Contoh: 50000" value="<?= $publication->price; ?>">
                            <div class="form-text small text-muted">Akan muncul tombol 'Beli via WhatsApp' bagi pengunjung.</div>
                        </div>
                    </div>
                    <script>
                        document.getElementById('isPaidToggle').addEventListener('change', function() {
                            document.getElementById('priceInputWrapper').style.display = this.checked ? 'block' : 'none';
                        });
                    </script>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="<?= BASE_URL; ?>admin/publications" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small text-center d-block">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>
