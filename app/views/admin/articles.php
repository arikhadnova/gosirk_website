<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <span class="admin-header-badge d-inline-block">PORTOFOLIO & MEDIA / <?= ($data['active'] == 'articles' ? 'ARTIKEL BLOG' : 'LIBRARY') ?></span>
        <h1 class="fw-bold mb-0"><?= ($data['active'] == 'articles' ? 'Kelola Artikel Blog' : 'Kelola Library') ?></h1>
        <p class="text-muted small mb-0"><?= ($data['active'] == 'articles' ? 'Kelola konten berita, edukasi, dan update terbaru dari GoSirk.' : 'Kelola resource edukatif, series belajar, dan tutorial.') ?></p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/<?= $data['active'] ?>/create" class="btn btn-primary px-4 py-2">
            <i class="fas fa-plus me-2"></i> <?= ($data['active'] == 'articles' ? 'Tulis Artikel Baru' : 'Tambah Resource Baru') ?>
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
            <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="Cari artikel berdasarkan judul..." id="articleSearch">
        </div>
    </div>
    <div class="col-lg-7 d-flex justify-content-lg-end flex-wrap gap-2">
        <select class="form-select w-auto border-0 shadow-sm rounded-pill px-4" style="min-width: 180px;" id="articleCategory">
            <option value="">Semua Kategori</option>
            <?php foreach (Article_model::usedCategories($articles ?? []) as $cat) : ?>
                <option value="<?= htmlspecialchars(strtolower($cat)) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-select w-auto border-0 shadow-sm rounded-pill px-4" style="min-width: 150px;" id="articleStatus">
            <option value="">Semua Status</option>
            <option value="published">Terbit</option>
            <option value="draft">Draf</option>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th style="width: 80px;">Cover</th>
                <th>Judul Artikel</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody id="articleTableBody">
            <?php if (empty($articles)) : ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">Belum ada artikel. Klik "Tulis Artikel Baru" untuk memulai.</div>
                    </td>
                </tr>
            <?php else : ?>
                <?php foreach ($articles as $a) : ?>
                    <tr class="shadow-sm" data-category="<?= htmlspecialchars(strtolower($a->category ?? '')) ?>" data-status="<?= htmlspecialchars($a->status) ?>">
                        <td>
                            <?php if ($a->image) : ?>
                                <img src="<?= ASSETS_URL; ?>img/blog/<?= $a->image; ?>" alt="Article" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else : ?>
                                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-newspaper text-muted opacity-50"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL; ?>admin/articles/edit/<?= $a->id; ?>" class="fw-bold text-dark text-decoration-none hover-primary article-title"><?= $a->title_id; ?></a>
                            <div class="text-muted extra-small mt-1"><?= $a->author; ?></div>
                        </td>
                        <td><span class="badge bg-light text-muted border-0 extra-small px-2 py-1"><?= $a->category; ?></span></td>
                        <td>
                            <?php if ($a->status == 'published') : ?>
                                <span class="badge bg-success bg-opacity-10 text-success extra-small fw-bold">Terbit</span>
                            <?php else : ?>
                                <span class="badge bg-warning bg-opacity-10 text-orange extra-small fw-bold">Draf</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="text-muted extra-small"><?= date('d M Y', strtotime($a->created_at)); ?></span></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= BASE_URL; ?>admin/articles/edit/<?= $a->id; ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL; ?>admin/articles_delete/<?= $a->id; ?>" class="btn btn-sm btn-light text-danger btn-delete-confirm" title="Hapus" data-confirm-message="Artikel ini akan dihapus permanen!"><i class="fas fa-trash"></i></a>
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
    const searchInput = document.getElementById('articleSearch');
    const tableRows = document.querySelectorAll('#articleTableBody tr');

    const categorySelect = document.getElementById('articleCategory');
    const statusSelect = document.getElementById('articleStatus');

    // Search, category and status work together
    function applyFilters() {
        const term = searchInput.value.toLowerCase();
        const cat = categorySelect.value, status = statusSelect.value;
        tableRows.forEach(row => {
            const titleEl = row.querySelector('.article-title');
            if (!titleEl) return;
            const text = titleEl.innerText.toLowerCase() + ' ' + (row.dataset.category || '');
            const show = text.includes(term) && (!cat || row.dataset.category === cat) && (!status || row.dataset.status === status);
            row.style.display = show ? '' : 'none';
        });
    }
    searchInput.addEventListener('input', applyFilters);
    categorySelect.addEventListener('change', applyFilters);
    statusSelect.addEventListener('change', applyFilters);
});
</script>
