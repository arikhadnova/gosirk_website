<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/services" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block text-uppercase">LAYANAN / KATEGORI / TAMBAH</span>
            <h1 class="fw-bold mb-0">Tambah Layanan Utama</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/services_store" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-dark">Informasi Layanan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Nama Layanan</label>
                        <input type="text" name="name_id" class="form-control form-control-lg" placeholder="Contoh: Pengelolaan Sampah Kawasan" <?= FormRules::attrs('service', 'name_id', 'store') ?>>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Deskripsi Layanan</label>
                        <textarea name="description_id" class="form-control" rows="8" placeholder="Jelaskan detail layanan..." <?= FormRules::attrs('service', 'description_id', 'store') ?>></textarea>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan dibuat otomatis.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-dark">Gambar & Urutan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Gambar Layanan</label>
                        <div class="form-group mb-3">
                            <div class="image-upload-wrapper p-3 border-2 border-dashed rounded-4 text-center bg-light" id="imageDropZone" style="cursor: pointer; border: 2px dashed #CBD5E0 !important;">
                                <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">
                                <div id="imagePreview" class="d-none mb-3">
                                    <img src="" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px;">
                                </div>
                                <div id="uploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt text-primary display-6 mb-2"></i>
                                    <p class="small text-muted mb-0">Klik atau seret gambar ke sini</p>
                                    <p class="extra-small text-muted">Format: JPG, PNG, WEBP (Maks. 10 MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Urutan Tampil</label>
                        <input type="number" name="order_priority" class="form-control" value="1" <?= FormRules::attrs('service', 'order_priority', 'store') ?>>
                    </div>
                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> Simpan Layanan
                    </button>
                    <a href="<?= BASE_URL; ?>admin/services" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small text-center d-block">Batal dan Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview.querySelector('img');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const imageDropZone = document.getElementById('imageDropZone');

    imageDropZone.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
                uploadPlaceholder.classList.add('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Drag and drop
    imageDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageDropZone.style.borderColor = '#0d4a7c';
        imageDropZone.style.backgroundColor = '#f0f7ff';
    });

    imageDropZone.addEventListener('dragleave', () => {
        imageDropZone.style.borderColor = '#CBD5E0';
        imageDropZone.style.backgroundColor = '#f8f9fa';
    });

    imageDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        imageDropZone.style.borderColor = '#CBD5E0';
        imageDropZone.style.backgroundColor = '#f8f9fa';
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            imageInput.files = e.dataTransfer.files;
            const reader = new FileReader();
            reader.onload = function(ex) {
                previewImg.src = ex.target.result;
                imagePreview.classList.remove('d-none');
                uploadPlaceholder.classList.add('d-none');
            }
            reader.readAsDataURL(e.dataTransfer.files[0]);
        }
    });
});
</script>
