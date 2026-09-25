<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / FAQ</span>
        <h1 class="fw-bold mb-0">Kelola FAQ</h1>
        <p class="text-muted small mb-0">Kelola pertanyaan sering diajukan untuk setiap kategori halaman.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/faqs_create" class="btn btn-primary fw-bold px-4">
            <i class="fas fa-plus me-2"></i> Tambah FAQ
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
        <form action="<?= BASE_URL ?>admin/faqs" method="GET" id="filterForm">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Filter Halaman</label>
                    <select name="page" class="form-select bg-light border-0 small" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <option value="gi" <?= (isset($_GET['page']) && $_GET['page'] == 'gi') ? 'selected' : '' ?>>Capacity Building (GI)</option>
                        <option value="implentasi_partner" <?= (isset($_GET['page']) && $_GET['page'] == 'implentasi_partner') ? 'selected' : '' ?>>Implementasi Partner</option>
                        <option value="konsultan" <?= (isset($_GET['page']) && $_GET['page'] == 'konsultan') ? 'selected' : '' ?>>Konsultansi</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mt-4">
    <?php if (empty($faqs)) : ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm p-5 text-center">
                <div class="text-muted">
                    <i class="fas fa-question-circle fa-3x mb-3 opacity-25"></i>
                    <p>Belum ada FAQ. Klik tombol di atas untuk menambah.</p>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0 small fw-bold text-muted">URUTAN</th>
                                <th class="py-3 border-0 small fw-bold text-muted">PERTANYAAN</th>
                                <th class="py-3 border-0 small fw-bold text-muted">HALAMAN</th>
                                <th class="py-3 border-0 small fw-bold text-muted">STATUS</th>
                                <th class="py-3 border-0 small fw-bold text-muted text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faqs as $f) : ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-light text-dark fw-bold"><?= $f->sort_order ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small mb-1"><?= $f->question_id ?></div>
                                        <div class="text-muted extra-small italic">"<?= $f->question_en ?>"</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary extra-small">
                                            <?= strtoupper(str_replace('_', ' ', $f->page)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($f->status == 'active') : ?>
                                            <span class="badge bg-success bg-opacity-10 text-success extra-small">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary extra-small">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?= BASE_URL ?>admin/faqs_edit/<?= $f->id ?>" class="btn btn-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                                <i class="fas fa-edit text-primary extra-small"></i>
                                            </a>
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center delete-btn" style="width: 32px; height: 32px;" data-id="<?= $f->id ?>" data-name="<?= addslashes($f->question_id) ?>" title="Hapus">
                                                <i class="fas fa-trash text-danger extra-small"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "FAQ '" + name + "' akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_URL ?>admin/faqs_delete/' + id;
                }
            });
        });
    });
});
</script>
