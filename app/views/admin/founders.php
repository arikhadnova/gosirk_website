<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / FOUNDER</span>
        <h1 class="fw-bold mb-0">Kelola Founder</h1>
        <p class="text-muted small mb-0">Kelola daftar founder yang ditampilkan di halaman About.</p>
    </div>
     <div>
        <a href="<?= BASE_URL; ?>admin/founders/create" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Tambah Founder
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<!-- Founders List -->
<div class="row mb-5 mt-4">
    <div class="col-12">
        <div class="row g-4">
            <?php if (empty($founders)): ?>
                <div class="col-12 text-center py-5">
                    <div class="bg-white rounded-4 p-5 border" style="border-style: dashed !important;">
                        <i class="fas fa-users-slash fa-3x text-muted opacity-25 mb-3"></i>
                        <h6 class="text-muted">Belum ada data founder.</h6>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($founders as $f) : ?>
                    <div class="col-xl-4 col-md-6 founder-item">
                        <div class="card h-100 border-0 p-4 text-center founder-card-modern shadow-sm position-relative">
                            <div class="founder-actions-overlay">
                                <a href="<?= BASE_URL; ?>admin/founders/edit/<?= $f->id; ?>" class="btn btn-sm btn-light text-primary shadow-sm rounded-5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                    <i class="fas fa-edit extra-small"></i>
                                </a>
                                <a href="<?= BASE_URL; ?>admin/founders/delete/<?= $f->id; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus founder ini?')" class="btn btn-sm btn-light text-danger shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus">
                                    <i class="fas fa-trash extra-small"></i>
                                </a>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <?php if ($f->image) : ?>
                                    <img src="<?= ASSETS_URL; ?>img/<?= $f->image; ?>" alt="<?= $f->name; ?>" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f8f9fa;">
                                <?php else : ?>
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <i class="fas fa-user fa-3x text-muted opacity-25"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h5 class="fw-bold mb-1 text-dark"><?= $f->name; ?></h5>
                            <p class="text-muted small mb-2"><?= $f->role_id; ?></p>
                            <?php if($f->linkedin_url && $f->linkedin_url != '#'): ?>
                                <a href="<?= $f->linkedin_url; ?>" target="_blank" class="text-primary small mb-3"><i class="fab fa-linkedin"></i> LinkedIn</a>
                            <?php endif; ?>
                            
                            <div class="mt-3 pt-3 border-top">
                                <p class="text-muted small fst-italic text-truncate" style="max-width: 100%;">
                                    "<?= $f->quote_id; ?>"
                                </p>
                            </div>
                            <span class="badge bg-light text-muted position-absolute bottom-0 start-50 translate-middle-x mb-2">Order: <?= $f->display_order; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.founder-actions-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 8px;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 10;
}
.founder-card-modern:hover .founder-actions-overlay {
    opacity: 1;
}
.founder-card-modern {
    transition: transform 0.3s ease;
    border-radius: 16px;
    background: #fff;
    padding-bottom: 50px !important;
}
.founder-card-modern:hover {
    transform: translateY(-5px);
}
</style>
