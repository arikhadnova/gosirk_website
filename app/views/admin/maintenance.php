<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">SYSTEM / MAINTENANCE</span>
    <h1 class="fw-bold mb-0">Maintenance Mode</h1>
    <p class="text-muted small mb-0">Kontrol akses publik ke website GoSirk selama masa pemeliharaan.</p>
</div>

<div class="row mb-4">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-box stat-icon-danger me-3">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Konfigurasi Akses</h5>
                        <small class="text-muted">Aktifkan untuk menampilkan halaman maintenance ke publik.</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL; ?>admin/update_maintenance" method="POST">
                    <div class="maintenance-toggle-container p-4 border rounded-4 mb-4 text-center <?= ($is_maintenance == '1') ? 'bg-light-danger' : 'bg-light-success' ?>">
                        <div class="mb-3">
                            <i class="fas <?= ($is_maintenance == '1') ? 'fa-lock text-danger' : 'fa-globe text-success' ?> fa-3x"></i>
                        </div>
                        <h4 class="fw-bold"><?= ($is_maintenance == '1') ? 'Website SEDANG Pemeliharaan' : 'Website Aktif Normal' ?></h4>
                        <p class="text-muted small">
                            <?= ($is_maintenance == '1') 
                                ? 'Pengunjung publik saat ini hanya dapat melihat halaman maintenance.' 
                                : 'Seluruh pengunjung dapat mengakses website seperti biasa.' ?>
                        </p>
                        
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input custom-switch" type="checkbox" name="is_maintenance" id="is_maintenance" <?= ($is_maintenance == '1') ? 'checked' : '' ?> style="width: 3.5rem; height: 1.75rem;">
                        </div>
                    </div>

                    <div class="alert alert-info border-0 rounded-4 smaller shadow-none mb-4">
                        <div class="d-flex">
                            <i class="fas fa-info-circle mt-1 me-3"></i>
                            <div>
                                <small><strong>Catatan Penting:</strong> Walaupun mode maintenance aktif, Anda sebagai <strong>Admin</strong> tetap dapat mengakses seluruh halaman website selama sesi login masih aktif.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 fw-bold">
                            <i class="fas fa-save me-2"></i> Simpan Status Pemeliharaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0 text-dark">Pratinjau Halaman Maintenance</h5>
            </div>
            <div class="card-body p-4">
                <div class="border rounded-4 overflow-hidden position-relative" style="height: 350px;">
                    <iframe src="<?= BASE_URL ?>maintenance.php" style="width: 100%; height: 100%; border: none; transform: scale(0.85); transform-origin: top center;"></iframe>
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-white border-top text-center">
                        <a href="<?= BASE_URL ?>maintenance.php" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                            <i class="fas fa-external-link-alt me-2"></i> Lihat Halaman Penuh
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-danger { background-color: rgba(220, 53, 69, 0.05); border: 1px dashed #dc3545 !important; }
.bg-light-success { background-color: rgba(40, 167, 69, 0.05); border: 1px dashed #28a745 !important; }
.custom-switch { cursor: pointer; }
.stat-icon-danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
