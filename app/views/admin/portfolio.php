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

<?php $locLabel = $location ? Portfolio_model::LOCATIONS[$location] : ''; ?>
<!-- Search & place -->
<div class="row mb-4 g-3 align-items-end">
    <div class="col-lg-6">
        <div class="card border-0 shadow-none bg-white p-1 rounded-pill d-flex flex-row align-items-center px-4" style="border: 1px solid #E2E8F0 !important;">
            <i class="fas fa-search text-muted me-3"></i>
            <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="Cari proyek berdasarkan nama, klien, atau lokasi..." id="portfolioSearch">
        </div>
    </div>
    <div class="col-lg-6">
        <label for="filterLocation" class="form-label small fw-semibold text-muted mb-1 ms-3">Tampilkan &amp; atur urutan untuk</label>
        <select class="form-select border-0 shadow-sm rounded-pill px-4 py-2" style="height: 50px;" id="filterLocation"
                onchange="location.href = '<?= BASE_URL ?>admin/portfolio' + (this.value ? '?lokasi=' + encodeURIComponent(this.value) : '')">
            <option value="">Semua portofolio</option>
            <?php foreach (Portfolio_model::LOCATIONS as $key => $label) : ?>
                <option value="<?= $key ?>" <?= $key === $location ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<?php if (!empty($home_without_section)) : ?>
<div class="alert alert-warning small py-2 mb-3">
    <i class="fas fa-exclamation-triangle me-1"></i>
    <?= count($home_without_section) ?> portofolio dicentang tampil di Home tapi belum punya <strong>Section di Home</strong>, jadi belum tampil di Home:
    <?= implode(', ', array_map(fn($p) => '<a href="' . BASE_URL . 'admin/portfolio/edit/' . (int) $p->id . '">' . htmlspecialchars($p->title_id) . '</a>', $home_without_section)) ?>.
</div>
<?php endif; ?>

<?php if ($location) : ?>
<div class="alert alert-info small py-2 mb-3" id="sortHint">
    <i class="fas fa-arrows-alt me-1"></i>
    Urutan di bawah sama dengan urutan di <strong><?= htmlspecialchars($locLabel) ?></strong> pada website.
    <?php if (count($portfolios) > 1) : ?>Geser baris (pegang ikon <i class="fas fa-grip-vertical"></i>) untuk mengubahnya; urutan langsung tersimpan dan hanya berlaku di tempat ini.<?php endif; ?>
    Portofolio baru otomatis masuk di urutan paling akhir.
</div>
<?php else : ?>
<p class="text-muted small mb-3">
    <i class="fas fa-info-circle me-1"></i> Untuk mengatur urutan tampil di website, pilih tempatnya di kolom "Tampilkan &amp; atur urutan untuk". Setiap tempat punya urutannya sendiri.
</p>
<?php endif; ?>

<?php if (empty($portfolios)) : ?>
    <div class="text-center py-5">
        <div class="bg-light rounded-4 p-5 border border-dashed">
            <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
            <p class="text-muted"><?= $location ? 'Belum ada portofolio yang tampil di ' . htmlspecialchars($locLabel) . '.' : 'Belum ada proyek yang ditambahkan. Klik tombol di atas untuk memulai.' ?></p>
        </div>
    </div>
<?php else : ?>
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="portfolio-list-head d-flex align-items-center gap-3 px-3 py-2">
        <?php if ($location) : ?><span class="col-handle"></span><?php endif; ?>
        <span class="sort-position text-end">No</span>
        <span class="portfolio-thumb-col d-none d-sm-block">Gambar</span>
        <span class="flex-grow-1 min-w-0 text-truncate">Judul &amp; Klien</span>
        <span class="d-none d-md-block text-end">Tipe &amp; Tampil di</span>
        <span class="portfolio-actions text-end">Aksi</span>
    </div>
    <div class="portfolio-list" id="portfolioGrid">
        <?php foreach ($portfolios as $i => $p) : ?>
            <?php $places = array_filter(['home' => $p->show_home ? 'Home' : '', 'partnership' => $p->show_partnership ? 'Partnership' : '', 'gi' => $p->show_gi ? 'GoSirk Institute' : '']); ?>
            <div class="portfolio-item d-flex align-items-center gap-3 px-3 py-2" data-id="<?= $p->id ?>">
                <?php if ($location) : ?>
                    <button type="button" class="btn btn-sm btn-link text-muted sort-handle col-handle px-1" title="Geser untuk mengubah urutan" aria-label="Geser untuk mengubah urutan"><i class="fas fa-grip-vertical"></i></button>
                <?php endif; ?>
                <span class="sort-position text-muted fw-bold small text-end" title="Urutan tampil"><?= $i + 1 ?></span>
                <div class="portfolio-thumb rounded-2 bg-light d-none d-sm-flex align-items-center justify-content-center flex-shrink-0 overflow-hidden">
                    <?php if ($p->cover_image) : ?>
                        <img src="<?= ASSETS_URL . 'img/portfolio/' . $p->cover_image; ?>" class="w-100 h-100 object-fit-cover" alt="" loading="lazy">
                    <?php else : ?>
                        <i class="<?= $p->icon_name ?: 'fas fa-briefcase'; ?> text-muted opacity-50"></i>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <a href="<?= BASE_URL; ?>admin/portfolio/edit/<?= $p->id; ?>" class="fw-bold text-dark text-decoration-none d-block text-truncate portfolio-title"><?= $p->title_id; ?></a>
                    <div class="extra-small text-muted text-truncate portfolio-desc"><?= htmlspecialchars(trim(($p->client_name ? $p->client_name . ' · ' : '') . mb_substr(strip_tags((string) $p->description_id), 0, 90))) ?></div>
                </div>
                <div class="d-none d-md-flex flex-wrap gap-1 justify-content-end portfolio-places">
                    <span class="badge bg-primary bg-opacity-10 text-primary extra-small"><?= htmlspecialchars($partner_types[strtolower((string) $p->partner_type)]->label_id ?? $p->partner_type); ?></span>
                    <?php foreach ($places as $label) : ?>
                        <span class="badge bg-light text-secondary border extra-small"><?= $label ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex gap-2 flex-shrink-0 justify-content-end portfolio-actions">
                    <a href="<?= BASE_URL; ?>admin/portfolio/edit/<?= $p->id; ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                    <a href="<?= BASE_URL; ?>admin/portfolio_delete/<?= $p->id; ?>" class="btn btn-sm btn-light text-danger btn-delete-confirm" title="Hapus" data-confirm-message="Proyek ini akan dihapus permanen!"><i class="fas fa-trash"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<style>
.portfolio-list .portfolio-item { background: #fff; border-bottom: 1px solid #EEF2F7; }
.portfolio-list .portfolio-item:last-child { border-bottom: 0; }
.portfolio-list .portfolio-item:hover { background: #F8FAFC; }
.portfolio-list-head { background: #F8FAFC; border-bottom: 1px solid #EEF2F7; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #64748B; }
.col-handle { width: 32px; flex-shrink: 0; justify-content: center; }
.portfolio-thumb, .portfolio-thumb-col { width: 56px; flex-shrink: 0; }
.portfolio-thumb { height: 40px; }
.portfolio-actions { width: 84px; flex-shrink: 0; }

.sort-position { width: 1.75rem; flex-shrink: 0; }
.portfolio-places { max-width: 40%; }
.min-w-0 { min-width: 0; }
.sort-handle { cursor: grab; }
.sort-ghost { opacity: .4; }
.sorting-disabled .sort-handle { visibility: hidden !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('portfolioSearch');
    const cards = document.querySelectorAll('#portfolioGrid > div.portfolio-item');
    const grid = document.getElementById('portfolioGrid');

    searchInput.addEventListener('input', () => {
        const term = searchInput.value.toLowerCase();
        cards.forEach(col => {
            const text = col.querySelector('.portfolio-title').innerText.toLowerCase() + ' ' + col.querySelector('.portfolio-desc').innerText.toLowerCase();
            col.classList.toggle('d-none', !text.includes(term));
        });
    });

    // Drag-and-drop order of the chosen place (saved right away). Off while searching: hidden rows would make the order unclear.
    const place = <?= json_encode($location) ?>;
    if (!place || !window.Sortable || cards.length < 2) return;
    const sortable = Sortable.create(grid, {
        handle: '.sort-handle',
        draggable: '.portfolio-item',
        ghostClass: 'sort-ghost',
        animation: 150,
        onEnd: async (e) => {
            if (e.oldIndex === e.newIndex) return;
            const items = [...grid.querySelectorAll('.portfolio-item')];
            items.forEach((el, i) => { el.querySelector('.sort-position').textContent = i + 1; });
            const fd = new FormData();
            fd.append('location', place);
            items.forEach((el) => fd.append('ids[]', el.dataset.id));
            try {
                const res = await fetch('<?= BASE_URL ?>admin/portfolio_reorder', { method: 'POST', body: fd }).then((r) => r.json());
                if (res.status !== 'success') throw new Error(res.message);
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Urutan disimpan', showConfirmButton: false, timer: 1500 });
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Urutan gagal disimpan', text: (err && err.message) || 'Muat ulang halaman lalu coba lagi.' });
            }
        }
    });
    searchInput.addEventListener('input', () => {
        const searching = searchInput.value.trim() !== '';
        sortable.option('disabled', searching);
        grid.classList.toggle('sorting-disabled', searching);
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
