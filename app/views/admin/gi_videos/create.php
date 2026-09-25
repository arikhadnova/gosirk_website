<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/gi_videos" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block text-uppercase">DASHBOARD / CAPACITY BUILDING / VIDEO / TAMBAH</span>
            <h1 class="fw-bold mb-0">Tambah Video GI Baru</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/gi_videos_store" method="POST" enctype="multipart/form-data">
    <div class="card border-0 shadow-sm rounded-4" style="max-width: 640px;">
        <div class="card-header bg-white border-bottom p-4">
            <h5 class="fw-bold mb-0 text-dark">Informasi Video</h5>
        </div>
        <div class="card-body p-4">
            <div class="mb-4">
                <label class="form-label fw-bold small text-dark">URL Video YouTube</label>
                <input type="url" name="url" id="yt_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." <?= FormRules::attrs('gi_video', 'url', 'store') ?>>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small text-dark">Thumbnail Custom <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small class="text-muted extra-small d-block mt-2">Jika kosong, thumbnail akan diambil otomatis dari YouTube.</small>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small text-dark">Urutan Prioritas</label>
                <input type="number" name="order_priority" class="form-control" value="0" style="max-width: 160px;" <?= FormRules::attrs('gi_video', 'order_priority', 'store') ?>>
            </div>
            <div class="d-flex align-items-center gap-3 pt-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                    <i class="fas fa-save me-2"></i> Simpan Video
                </button>
                <a href="<?= BASE_URL; ?>admin/gi_videos" class="btn btn-link text-decoration-none text-muted small">Batal dan Kembali</a>
            </div>
        </div>
    </div>
</form>
