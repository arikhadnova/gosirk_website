<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / PUBLIKASI</span>
        <h1 class="fw-bold mb-0">Kelola Publikasi</h1>
        <p class="text-muted small mb-0">Kelola laporan tahunan, policy brief, modul, dan referensi regulasi eksternal.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/publications/create" class="btn btn-primary">
            <i class="fas fa-file-upload me-2"></i> Tambah Publikasi Baru
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
    <div class="col-lg-5">
        <div class="card border-0 shadow-none bg-white p-1 rounded-pill d-flex flex-row align-items-center px-4" style="border: 1px solid #E2E8F0 !important;">
            <i class="fas fa-search text-muted me-3"></i>
            <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="Cari publikasi atau laporan..." id="pubSearch">
        </div>
    </div>
    <div class="col-lg-7 d-flex justify-content-lg-end flex-wrap gap-2">
        <select class="form-select w-auto border-0 shadow-sm rounded-pill px-4" style="min-width: 200px;" id="pubType">
            <option value="">Semua Tipe</option>
            <option value="gosirk">Publikasi GoSirk</option>
            <option value="reference">Referensi</option>
        </select>
        <?php $pubYears = array_unique(array_map(fn($x) => date('Y', strtotime($x->created_at)), $publications ?? [])); rsort($pubYears); ?>
        <select class="form-select w-auto border-0 shadow-sm rounded-pill px-4" style="min-width: 150px;" id="pubYear">
            <option value="">Semua Tahun</option>
            <?php foreach ($pubYears as $y) : ?><option value="<?= $y ?>"><?= $y ?></option><?php endforeach; ?>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th style="width: 80px;">Format</th>
                <th>Judul Publikasi</th>
                <th>Kategori</th>
                <th class="text-center">Tahun</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody id="pubTableBody">
            <?php if (empty($publications)) : ?>
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">Belum ada publikasi. Klik "Tambah Publikasi Baru" untuk memulai.</div>
                    </td>
                </tr>
            <?php else : ?>
                <?php foreach ($publications as $pub) : ?>
                    <tr class="shadow-sm" data-type="<?= htmlspecialchars($pub->type) ?>" data-year="<?= date('Y', strtotime($pub->created_at)) ?>">
                        <td>
                            <div class="stat-icon-box stat-icon-orange m-0" style="width: 45px; height: 45px;">
                                <i class="fas fa-file-pdf fs-6 text-danger"></i>
                            </div>
                        </td>
                        <td>
                            <a href="<?= BASE_URL; ?>admin/publications/edit/<?= $pub->id; ?>" class="fw-bold text-dark text-decoration-none hover-primary pub-title-link"><?= $pub->title_id; ?></a>
                            <div class="text-muted extra-small mt-1"><?= substr(strip_tags($pub->description_id ?? ''), 0, 100); ?>...</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-muted border-0 small px-2">
                                <?= $pub->type == 'gosirk' ? 'GoSirk Original' : 'Reference'; ?>
                            </span>
                        </td>
                        <td class="text-center"><span class="text-dark fw-bold small"><?= date('Y', strtotime($pub->created_at)); ?></span></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= BASE_URL; ?>admin/publications/edit/<?= $pub->id; ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL; ?>admin/publications_delete/<?= $pub->id; ?>" class="btn btn-sm btn-light text-danger btn-delete-confirm" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pubSearch');
    const tableRows = document.querySelectorAll('#pubTableBody tr');

    const typeSelect = document.getElementById('pubType');
    const yearSelect = document.getElementById('pubYear');

    // Search, type and year work together
    function applyFilters() {
        const term = searchInput.value.toLowerCase();
        const type = typeSelect.value, year = yearSelect.value;
        tableRows.forEach(row => {
            const titleEl = row.querySelector('.pub-title-link');
            if (!titleEl) return;
            const text = titleEl.innerText.toLowerCase() + ' ' + (row.querySelector('.text-muted')?.innerText.toLowerCase() || '');
            const show = text.includes(term) && (!type || row.dataset.type === type) && (!year || row.dataset.year === year);
            row.style.display = show ? '' : 'none';
        });
    }
    searchInput.addEventListener('input', applyFilters);
    typeSelect.addEventListener('change', applyFilters);
    yearSelect.addEventListener('change', applyFilters);
});
</script>
