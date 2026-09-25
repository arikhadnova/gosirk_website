<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/publications" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left text-muted"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block">PUBLIKASI / TAMBAH</span>
            <h1 class="fw-bold mb-0">Tambah Publikasi Baru</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/publications_store" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Informasi Publikasi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Judul Publikasi</label>
                        <input type="text" name="title_id" class="form-control" placeholder="Contoh: Laporan Tahunan 2025" <?= FormRules::attrs('publication', 'title_id', 'store') ?>>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark">Deskripsi Singkat</label>
                        <textarea name="description_id" class="form-control" rows="5" placeholder="Tulis ringkasan isi publikasi..." <?= FormRules::attrs('publication', 'description_id', 'store') ?>></textarea>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan dibuat otomatis.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-dark">File & Detail</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Tipe Publikasi</label>
                        <select name="type" class="form-select" <?= FormRules::attrs('publication', 'type', 'store') ?>>
                            <option value="gosirk">GoSirk Publication</option>
                            <option value="reference">Reference / Legal</option>
                        </select>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">File Publikasi (PDF)</label>
                        <div class="p-3 border rounded-3 text-center bg-light border-dashed">
                            <i class="fas fa-file-pdf fa-2x text-danger mb-2 opacity-50"></i>
                            <input type="file" name="file_path" class="form-control form-control-sm" accept=".pdf">
                        </div>
                        <small class="text-muted extra-small">Upload file jika tersedia (Maks. 10 MB)</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Link Eksternal</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-link text-muted"></i></span>
                            <input type="url" name="external_link" class="form-control border-start-0 ps-0" placeholder="https://example.com/external-source" <?= FormRules::attrs('publication', 'external_link', 'store') ?>>
                        </div>
                        <small class="text-muted extra-small">Masukkan link ke sumber luar jika ada</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">File Preview (PDF)</label>
                        <div class="p-3 border rounded-3 text-center bg-light border-dashed">
                            <i class="fas fa-file-pdf fa-2x text-warning mb-2 opacity-50"></i>
                            <input type="file" name="preview_path" class="form-control form-control-sm" accept=".pdf">
                        </div>
                        <small class="text-muted extra-small">Upload 1-2 halaman awal untuk publikasi berbayar</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Thumbnail (gambar)</label>
                        <div class="p-3 border rounded-3 text-center bg-light border-dashed">
                            <i class="fas fa-image fa-2x text-muted mb-2 opacity-50"></i>
                            <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Status Berbayar</label>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_paid" id="isPaidToggle">
                            <label class="form-check-label small" for="isPaidToggle">Publikasi Berbayar</label>
                        </div>
                        <div id="priceInputWrapper" style="display: none;">
                            <label class="form-label small fw-bold text-dark">Harga (RP)</label>
                            <input type="number" name="price" class="form-control" placeholder="Contoh: 50000" value="0" <?= FormRules::attrs('publication', 'price', 'store') ?>>
                            <div class="form-text small text-muted">Akan muncul tombol 'Beli via WhatsApp' bagi pengunjung.</div>
                        </div>
                    </div>
                    <script>
                        document.getElementById('isPaidToggle').addEventListener('change', function() {
                            document.getElementById('priceInputWrapper').style.display = this.checked ? 'block' : 'none';
                        });
                    </script>
                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                        <i class="fas fa-cloud-upload-alt me-2"></i> Upload Publikasi
                    </button>
                    <a href="<?= BASE_URL; ?>admin/publications" class="btn btn-light w-100 py-2 mt-2 text-muted small">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
