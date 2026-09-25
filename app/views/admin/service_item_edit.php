<?php $item = $data['item']; ?>
<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/services_<?= $item->category ?>" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block text-uppercase">LAYANAN / <?= $item->category ?> / EDIT</span>
            <h1 class="fw-bold mb-0">Edit Item Layanan</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/service_item_update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $item->id ?>">
    <input type="hidden" name="category" value="<?= $item->category ?>">
    
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Informasi Layanan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Judul</label>
                        <input type="text" name="title_id" class="form-control form-control-lg" value="<?= $item->title_id ?>" placeholder="Masukkan judul dalam Bahasa Indonesia" <?= FormRules::attrs('service_item', 'title_id', 'update') ?>>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Deskripsi</label>
                        <textarea name="description_id" class="form-control" rows="8" placeholder="Masukkan deskripsi detail dalam Bahasa Indonesia..." <?= FormRules::attrs('service_item', 'description_id', 'update') ?>><?= $item->description_id ?></textarea>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan diperbarui otomatis saat disimpan.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Attributes & Action</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Gambar Layanan</label>
                        <?php if (!empty($item->image)) : ?>
                            <div class="mb-3">
                                <img src="<?= ASSETS_URL ?>img/services/<?= $item->image ?>" alt="Preview" class="img-fluid rounded-3 shadow-sm border" style="max-height: 150px;">
                                <p class="text-muted extra-small mt-1 mb-0">Gambar saat ini</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*" <?= FormRules::attrs('service_item', 'image', 'update') ?>>
                        <small class="text-muted extra-small d-block mt-2">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Link URL / Slug (Optional)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fas fa-link"></i></span>
                            <input type="text" name="link_url" class="form-control" value="<?= $item->link_url ?>" placeholder="gi/detail/slug-layanan" <?= FormRules::attrs('service_item', 'link_url', 'update') ?>>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Prioritas Urutan</label>
                        <input type="number" name="order_priority" class="form-control" value="<?= $item->order_priority ?>" <?= FormRules::attrs('service_item', 'order_priority', 'update') ?>>
                        <small class="text-muted extra-small">Angka lebih kecil tampil lebih dulu.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Nama Partner (Optional)</label>
                        <input type="text" name="partner_name" class="form-control" value="<?= $item->partner_name ?>" placeholder="Misal: Sirk Norge & Norad" <?= FormRules::attrs('service_item', 'partner_name', 'update') ?>>
                        <small class="text-muted extra-small">Gunakan untuk Program Development.</small>
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Perbarui Item
                    </button>
                    
                    <a href="<?= BASE_URL; ?>admin/services_<?= $item->category ?>" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small text-center d-block">Batal dan Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>

