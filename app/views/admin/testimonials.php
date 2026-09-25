<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / TESTIMONI</span>
        <h1 class="fw-bold mb-0">Kelola Testimoni</h1>
        <p class="text-muted small mb-0">Kelola testimoni dari klien atau mitra untuk ditampilkan di halaman depan.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/testimonials_create" class="btn btn-primary fw-bold px-4">
            <i class="fas fa-plus me-2"></i> Tambah Testimoni
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 mt-3">
    <div class="card-body p-3">
        <form action="<?= BASE_URL ?>admin/testimonials" method="GET" id="filterForm">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Filter Halaman</label>
                    <select name="page" class="form-select bg-light border-0 small" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>

                        <option value="gi" <?= (isset($_GET['page']) && $_GET['page'] == 'gi') ? 'selected' : '' ?>>Capacity Building (GI)</option>
                        <option value="implentasi_partner" <?= (isset($_GET['page']) && $_GET['page'] == 'implentasi_partner') ? 'selected' : '' ?>>Implementasi Partner</option>
                        <option value="konsultan" <?= (isset($_GET['page']) && $_GET['page'] == 'konsultan') ? 'selected' : '' ?>>Konsultansi</option>
                        <option value="ggc" <?= (isset($_GET['page']) && $_GET['page'] == 'ggc') ? 'selected' : '' ?>>GoSirk Green Community</option>
                        <option value="go_ngompos_project" <?= (isset($_GET['page']) && $_GET['page'] == 'go_ngompos_project') ? 'selected' : '' ?>>Go Ngompos Project</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mt-4">
    <?php if (empty($testimonials)) : ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm p-5 text-center">
                <div class="text-muted">
                    <i class="fas fa-quote-left fa-3x mb-3 opacity-25"></i>
                    <p>Belum ada testimoni. Klik tombol di atas untuk menambah.</p>
                </div>
            </div>
        </div>
    <?php else : ?>
        <?php foreach ($testimonials as $t) : ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; overflow: hidden;">
                                <?php if ($t->image) : ?>
                                    <img src="<?= ASSETS_URL . 'img/testimonials/' . $t->image ?>" class="w-100 h-100 object-fit-cover shadow-sm">
                                <?php else : ?>
                                    <i class="fas fa-user text-muted opacity-50"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= $t->name ?></h6>
                                <p class="mb-0 text-muted extra-small"><?= $t->role_id ?></p>
                            </div>
                            <div class="ms-auto d-flex flex-column align-items-end gap-1">
                                <span class="badge <?= $t->status == 'active' ? 'bg-success' : 'bg-secondary' ?> bg-opacity-10 <?= $t->status == 'active' ? 'text-success' : 'text-secondary' ?> extra-small">
                                    <?= ucfirst($t->status) ?>
                                </span>
                                <span class="badge bg-primary bg-opacity-10 text-primary extra-small">
                                    <i class="fas fa-file-alt me-1 ps-1"></i> <?= strtoupper($t->page ?? 'home') ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="testimonial-content mb-4 pt-3 position-relative">
                            <i class="fas fa-quote-left position-absolute top-0 start-0 text-primary opacity-10 fa-2x"></i>
                            <div class="extra-small text-muted mb-2">ID:</div>
                            <p class="small text-dark mb-3 pe-3 line-clamp-3 fst-italic">"<?= $t->content_id ?>"</p>
                            
                            <div class="extra-small text-muted mb-2">EN:</div>
                            <p class="small text-secondary mb-0 line-clamp-3 fst-italic">"<?= $t->content_en ?>"</p>
                        </div>
                    </div>
                    <div class="card-footer bg-light bg-opacity-50 border-0 p-3 d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>admin/testimonials_edit/<?= $t->id ?>" class="btn btn-sm btn-light text-primary px-3 fw-bold">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-light text-danger px-3 fw-bold" onclick="confirmDelete(<?= $t->id ?>)">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Testimoni?',
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>admin/testimonials_delete/' + id;
        }
    })
}
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>
