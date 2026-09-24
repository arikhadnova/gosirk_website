<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/services_<?= $data['category'] ?>" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block text-uppercase">SERVICES / <?= $data['category'] ?> / ADD</span>
            <h1 class="fw-bold mb-0">Tambah Item Layanan</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/service_item_store" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="category" value="<?= $data['category'] ?>">
    
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-dark">Informasi Layanan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Judul</label>
                        <input type="text" name="title_id" class="form-control form-control-lg" placeholder="Masukkan judul layanan..." required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Deskripsi</label>
                        <textarea name="description_id" class="form-control" rows="8" placeholder="Tuliskan deskripsi lengkap layanan..." required></textarea>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan dibuat otomatis.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-dark">Attributes & Action</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Gambar Utama Layanan</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted extra-small d-block mt-2">Rekomendasi: 800x600px (JPG/PNG/WEBP)</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Link URL / Slug (Optional)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fas fa-link"></i></span>
                            <input type="text" name="link_url" class="form-control" placeholder="Contoh: gi/detail/slug-item">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Prioritas Urutan</label>
                        <input type="number" name="order_priority" class="form-control" value="0">
                        <small class="text-muted extra-small">Item dengan angka lebih kecil akan muncul pertama.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Nama Partner (Optional)</label>
                        <input type="text" name="partner_name" class="form-control" placeholder="Contoh: Sirk Norge & Norad">
                        <small class="text-muted extra-small">Nama partner khusus kategori Program Development.</small>
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                        <i class="fas fa-cloud-upload-alt me-2"></i> Simpan Item Layanan
                    </button>
                    
                    <a href="<?= BASE_URL; ?>admin/services_<?= $data['category'] ?>" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small text-center d-block">Batalkan</a>
                </div>
            </div>
        </div>
    </div>
</form>
