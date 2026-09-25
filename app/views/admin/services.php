<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <span class="admin-header-badge d-inline-block text-uppercase">DASHBOARD / LAYANAN</span>
            <h1 class="fw-bold mb-0">Kategori Layanan</h1>
            <p class="text-muted small mb-0">Kelola kategori layanan utama yang ditampilkan di halaman depan.</p>
        </div>
        <a href="<?= BASE_URL; ?>admin/services/create" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Tambah Kategori
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<!-- Search Area -->
<div class="row mb-4 mt-2">
    <div class="col-lg-5">
        <div class="card border-0 shadow-none bg-white p-1 rounded-pill d-flex flex-row align-items-center px-4" style="border: 1px solid #E2E8F0 !important;">
            <i class="fas fa-search text-muted me-3"></i>
            <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="Cari kategori..." id="serviceSearch">
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 100px;">Gambar</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Total Items</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody id="serviceTableBody">
                <?php if (empty($services)) : ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">Belum ada kategori layanan.</div>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($services as $s) : ?>
                        <tr>
                            <td class="ps-4">
                                <div class="rounded-3 overflow-hidden bg-light" style="width: 60px; height: 45px;">
                                    <?php if (isset($s->image) && $s->image) : ?>
                                        <img src="<?= ASSETS_URL; ?>img/services/<?= $s->image; ?>" class="w-100 h-100 object-fit-cover">
                                    <?php else : ?>
                                        <div class="d-flex h-100 align-items-center justify-content-center text-muted">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <a href="<?= BASE_URL; ?>admin/services/edit/<?= $s->id; ?>" class="fw-bold text-dark text-decoration-none hover-primary service-title"><?= $s->name_id; ?></a>
                                <div class="extra-small text-muted"><?= $s->name_en; ?></div>
                            </td>
                            <td>
                                <div class="text-muted extra-small text-truncate" style="max-width: 300px;"><?= strip_tags($s->description_id); ?></div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border-0 px-3">Aktif</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= BASE_URL; ?>admin/services/edit/<?= $s->id; ?>" class="btn btn-sm btn-light text-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Edit"><i class="fas fa-edit small"></i></a>
                                    <a href="<?= BASE_URL; ?>admin/services_delete/<?= $s->id; ?>" class="btn btn-sm btn-light text-danger rounded-circle btn-delete-confirm" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Hapus"><i class="fas fa-trash small"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('serviceSearch');
    const tableRows = document.querySelectorAll('#serviceTableBody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            tableRows.forEach(row => {
                const titleEl = row.querySelector('.service-title');
                if (!titleEl) return;
                
                const title = titleEl.innerText.toLowerCase();
                const desc = row.innerText.toLowerCase(); // Search everything in row
                
                if (title.includes(term) || desc.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>

