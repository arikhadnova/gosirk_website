<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / PORTOFOLIO</span>
        <h1 class="fw-bold mb-0">Kelola Portofolio</h1>
        <p class="text-muted small mb-0">Kelola proyek dan rekam jejak kolaborasi GoSirk.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/portfolio_create" class="btn btn-primary px-4 py-2">
            <i class="fas fa-plus me-2"></i> Tambah Proyek Baru
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<!-- Search & Filter Area -->
<div class="row mb-4 g-3 align-items-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-none bg-white p-1 rounded-pill d-flex flex-row align-items-center px-4" style="border: 1px solid #E2E8F0 !important;">
            <i class="fas fa-search text-muted me-3"></i>
            <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="Cari proyek berdasarkan nama, klien, atau lokasi..." id="portfolioSearch">
        </div>
    </div>
    <div class="col-lg-4">
        <select class="form-select border-0 shadow-sm rounded-pill px-4 py-2" style="height: 50px;" id="filterCategory">
            <option value="all">Semua Kategori</option>
            <option value="capacity_building">Capacity Building</option>
            <option value="program_development">Program Development</option>
            <option value="consultancy">Consultancy</option>
        </select>
    </div>
</div>

<div class="row g-4" id="portfolioGrid">
    <?php if (empty($portfolios)) : ?>
        <div class="col-12 text-center py-5">
            <div class="bg-light rounded-4 p-5 border border-dashed">
                <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted">Belum ada proyek yang ditambahkan. Klik tombol di atas untuk memulai.</p>
            </div>
        </div>
    <?php else : ?>
        <?php foreach ($portfolios as $p) : ?>
            <div class="col-xl-4 col-md-6 portfolio-item" data-category="<?= $p->main_category; ?>">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="position-relative">
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <?php if ($p->cover_image) : ?>
                                <img src="<?= ASSETS_URL . 'img/portfolio/' . $p->cover_image; ?>" class="w-100 h-100 object-fit-cover" alt="Cover">
                            <?php else : ?>
                                <i class="<?= $p->icon_name ?: 'fas fa-briefcase'; ?> fa-3x text-muted opacity-25"></i>
                            <?php endif; ?>
                        </div>
                        <div class="position-absolute bottom-0 start-0 m-3">
                            <span class="badge bg-primary px-3 rounded-pill fw-bold extra-small"><?= strtoupper($p->partner_type); ?></span>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="extra-small text-muted mb-2 d-flex align-items-center gap-2">
                             <i class="far fa-calendar-alt"></i> <?= $p->year_start; ?> <?= $p->year_end ? '- ' . $p->year_end : ''; ?>
                        </div>
                        <h6 class="fw-bold mb-2 text-dark portfolio-title"><?= $p->title_id; ?></h6>
                        <p class="text-muted extra-small flex-grow-1 line-clamp-3 mb-4 portfolio-desc"><?= substr(strip_tags($p->description_id), 0, 100); ?>...</p>
                        
                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <a href="<?= BASE_URL; ?>admin/portfolio/edit/<?= $p->id; ?>" class="btn btn-sm btn-light text-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL; ?>admin/portfolio_delete/<?= $p->id; ?>" class="btn btn-sm btn-light text-danger btn-delete-confirm" title="Hapus" data-confirm-message="Proyek ini akan dihapus permanen!"><i class="fas fa-trash"></i></a>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success extra-small">Published</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation" class="mt-5">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
        </li>
    </ul>
</nav>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('portfolioSearch');
    const cards = document.querySelectorAll('#portfolioGrid > div.portfolio-item');

    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        cards.forEach(col => {
            const title = col.querySelector('.portfolio-title').innerText.toLowerCase();
            const desc = col.querySelector('.portfolio-desc').innerText.toLowerCase();
            if (title.includes(term) || desc.includes(term)) {
                col.style.display = 'block';
            } else {
                col.style.display = 'none';
            }
        });
    });
});
</script>
