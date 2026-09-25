<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/settings" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block">DASHBOARD / PARTNERSHIP SETTINGS</span>
            <h1 class="fw-bold mb-0">Pengaturan Halaman Partnership</h1>
            <p class="text-muted small mb-0">Kelola judul, deskripsi, foto kategori, dan proyek sedang berjalan secara dinamis.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/update_partnership_settings" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Community Partnership -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-users me-2"></i>1. Community Partnership</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Judul</label>
                                <input type="text" name="ps_comm_title_id" class="form-control" value="<?= $settings['ps_comm_title_id'] ?? 'Community Partnership' ?>" <?= FormRules::attrs('partnership_settings', 'ps_comm_title_id', 'update') ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Sub-judul</label>
                                <input type="text" name="ps_comm_sub_id" class="form-control" value="<?= $settings['ps_comm_sub_id'] ?? 'Focal on community empowerment and social impact.' ?>" <?= FormRules::attrs('partnership_settings', 'ps_comm_sub_id', 'update') ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi</label>
                                <textarea name="ps_comm_desc_id" class="form-control" rows="3" <?= FormRules::attrs('partnership_settings', 'ps_comm_desc_id', 'update') ?>><?= $settings['ps_comm_desc_id'] ?? '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Foto Kategori</label>
                            <div class="border rounded-4 p-2 text-center mb-3">
                                <?php if (isset($settings['ps_comm_img'])) : ?>
                                    <img src="<?= ASSETS_URL ?>img/<?= $settings['ps_comm_img'] ?>" class="img-fluid rounded-3 mb-2" style="max-height: 150px;">
                                <?php else : ?>
                                    <img src="<?= ASSETS_URL ?>img/IMG_8084.jpg" class="img-fluid rounded-3 mb-2" style="max-height: 150px;">
                                <?php endif; ?>
                                <input type="file" name="ps_comm_img" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Partnership -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-graduation-cap me-2"></i>2. Academic Partnership</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Judul</label>
                                <input type="text" name="ps_acad_title_id" class="form-control" value="<?= $settings['ps_acad_title_id'] ?? 'Academic Partnership' ?>" <?= FormRules::attrs('partnership_settings', 'ps_acad_title_id', 'update') ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Sub-judul</label>
                                <input type="text" name="ps_acad_sub_id" class="form-control" value="<?= $settings['ps_acad_sub_id'] ?? 'Collaboration with research and higher education institutions.' ?>" <?= FormRules::attrs('partnership_settings', 'ps_acad_sub_id', 'update') ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi</label>
                                <textarea name="ps_acad_desc_id" class="form-control" rows="3" <?= FormRules::attrs('partnership_settings', 'ps_acad_desc_id', 'update') ?>><?= $settings['ps_acad_desc_id'] ?? '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Foto Kategori</label>
                            <div class="border rounded-4 p-2 text-center mb-3">
                                <?php if (isset($settings['ps_acad_img'])) : ?>
                                    <img src="<?= ASSETS_URL ?>img/<?= $settings['ps_acad_img'] ?>" class="img-fluid rounded-3 mb-2" style="max-height: 150px;">
                                <?php else : ?>
                                    <img src="<?= ASSETS_URL ?>img/IMG_8084.jpg" class="img-fluid rounded-3 mb-2" style="max-height: 150px;">
                                <?php endif; ?>
                                <input type="file" name="ps_acad_img" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Program Partnership -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-briefcase me-2"></i>3. Program Partnership</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Judul</label>
                                <input type="text" name="ps_prog_title_id" class="form-control" value="<?= $settings['ps_prog_title_id'] ?? 'Program Partnership' ?>" <?= FormRules::attrs('partnership_settings', 'ps_prog_title_id', 'update') ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Sub-judul</label>
                                <input type="text" name="ps_prog_sub_id" class="form-control" value="<?= $settings['ps_prog_sub_id'] ?? 'Strategic partnerships with government and CSR.' ?>" <?= FormRules::attrs('partnership_settings', 'ps_prog_sub_id', 'update') ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi</label>
                                <textarea name="ps_prog_desc_id" class="form-control" rows="3" <?= FormRules::attrs('partnership_settings', 'ps_prog_desc_id', 'update') ?>><?= $settings['ps_prog_desc_id'] ?? '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Foto Kategori</label>
                            <div class="border rounded-4 p-2 text-center mb-3">
                                <?php if (isset($settings['ps_prog_img'])) : ?>
                                    <img src="<?= ASSETS_URL ?>img/<?= $settings['ps_prog_img'] ?>" class="img-fluid rounded-3 mb-2" style="max-height: 150px;">
                                <?php else : ?>
                                    <img src="<?= ASSETS_URL ?>img/IMG_8084.jpg" class="img-fluid rounded-3 mb-2" style="max-height: 150px;">
                                <?php endif; ?>
                                <input type="file" name="ps_prog_img" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12 mt-2">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0">Simpan Seluruh Perubahan</h6>
                        <p class="small mb-0 opacity-75">Perubahan akan langsung diterapkan pada halaman publik.</p>
                    </div>
                    <button type="submit" class="btn btn-warning px-5 fw-bold py-2 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
