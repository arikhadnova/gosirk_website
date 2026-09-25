<?php
// Pre-filter partners to make the view logic cleaner
$contributionPartners = [];
$networkPartners = [];

foreach ($partners as $p) {
    // Logic from original file to maintain backward capability
    if (($p->category ?? '') == 'contribution') {
        $contributionPartners[] = $p;
    } 
    // Logic from original file: anything not strictly contribution that matches network default
    elseif (($p->category ?? 'network') == 'network') {
        $networkPartners[] = $p;
    }
}
?>

<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / PARTNER</span>
        <h1 class="fw-bold mb-0">Kelola Partner</h1>
        <p class="text-muted small mb-0">Kelola daftar logo partner strategis yang bekerja sama dengan GoSirk.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/partners/create" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Tambah Partner Baru
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
        <div class="card border-0 shadow-none bg-white p-1 rounded-3 d-flex flex-row align-items-center px-3" style="border: 1px solid #E2E8F0 !important;">
            <i class="fas fa-search text-muted me-3"></i>
            <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="Cari nama partner..." id="partnerSearch">
        </div>
    </div>
</div>

<style>
    .partner-scroll-container {
        max-height: 600px; /* Approx 10 items height */
        overflow-y: auto;
        position: relative;
    }
    .partner-scroll-container thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa; /* bg-light */
        z-index: 2;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .partner-logo-img {
        max-height: 40px;
        max-width: 80px;
        object-fit: contain;
    }
</style>

<!-- CATEGORY 1: OUR CONTRIBUTION & PARTNER -->
<div class="row mb-5">
    <div class="col-12 mb-3 d-flex align-items-center">
        <div class="bg-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: rgba(25, 135, 84, 0.1) !important;">
            <i class="fas fa-handshake text-success"></i>
        </div>
        <h4 class="fw-bold mb-0">Our Contribution & Partner</h4>
        <span class="badge bg-success bg-opacity-10 text-success ms-3"><?= count($contributionPartners); ?> Items</span>
    </div>
    
    <div class="col-12">
        <?php if (!empty($contributionPartners)) : ?>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="partner-scroll-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3" width="50">#</th>
                                <th class="py-3" width="120">Logo</th>
                                <th class="py-3" width="25%">Nama Partner</th>
                                <th class="py-3">Kategori & Tipe</th>
                                <th class="pe-4 py-3 text-end" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contributionPartners as $index => $p) : ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $index + 1; ?></td>
                                <td>
                                    <?php if ($p->logo) : ?>
                                        <img src="<?= ASSETS_URL; ?>img/partners/<?= $p->logo; ?>" alt="<?= $p->name; ?>" class="partner-logo-img" onerror="this.src='<?= ASSETS_URL ?>img/<?= $p->logo ?>';">
                                    <?php else : ?>
                                        <i class="fas fa-building text-muted fa-lg"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <h6 class="fw-bold mb-0 text-dark"><?= $p->name; ?></h6>
                                </td>
                                <td>
                                    <span class="badge bg-light text-muted fw-normal border"><?= $p->type; ?></span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="<?= BASE_URL; ?>admin/partners/edit/<?= $p->id; ?>" class="btn btn-sm btn-light text-primary shadow-sm rounded-circle me-1" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Edit Partner">
                                        <i class="fas fa-edit extra-small"></i>
                                    </a>
                                    <a href="<?= BASE_URL; ?>admin/partners_delete/<?= $p->id; ?>" class="btn btn-sm btn-light text-danger shadow-sm rounded-circle btn-delete-confirm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Partner">
                                        <i class="fas fa-trash extra-small"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="bg-white rounded-4 p-4 text-center border" style="border-style: dashed !important;">
                <p class="text-muted mb-0 small">Belum ada partner di kategori kontribusi.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<hr class="my-5 opacity-0">

<!-- CATEGORY 2: OUR NETWORK -->
<div class="row">
    <div class="col-12 mb-3 d-flex align-items-center">
        <div class="bg-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: rgba(13, 202, 240, 0.1) !important;">
            <i class="fas fa-network-wired text-info"></i>
        </div>
        <h4 class="fw-bold mb-0">Our Network</h4>
        <span class="badge bg-info bg-opacity-10 text-info ms-3"><?= count($networkPartners); ?> Items</span>
    </div>

    <div class="col-12">
        <?php if (!empty($networkPartners)) : ?>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="partner-scroll-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3" width="50">#</th>
                                <th class="py-3" width="120">Logo</th>
                                <th class="py-3" width="25%">Nama Partner</th>
                                <th class="py-3">Kategori & Tipe</th>
                                <th class="pe-4 py-3 text-end" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($networkPartners as $index => $p) : ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $index + 1; ?></td>
                                <td>
                                    <?php if ($p->logo) : ?>
                                        <img src="<?= ASSETS_URL; ?>img/partners/<?= $p->logo; ?>" alt="<?= $p->name; ?>" class="partner-logo-img" onerror="this.src='<?= ASSETS_URL ?>img/<?= $p->logo ?>';">
                                    <?php else : ?>
                                        <i class="fas fa-building text-muted fa-lg"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <h6 class="fw-bold mb-0 text-dark"><?= $p->name; ?></h6>
                                </td>
                                <td>
                                    <span class="badge bg-light text-muted fw-normal border"><?= $p->type; ?></span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="<?= BASE_URL; ?>admin/partners/edit/<?= $p->id; ?>" class="btn btn-sm btn-light text-primary shadow-sm rounded-circle me-1" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Edit Partner">
                                        <i class="fas fa-edit extra-small"></i>
                                    </a>
                                    <a href="<?= BASE_URL; ?>admin/partners_delete/<?= $p->id; ?>" class="btn btn-sm btn-light text-danger shadow-sm rounded-circle btn-delete-confirm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Partner">
                                        <i class="fas fa-trash extra-small"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="text-center py-5">
                <div class="bg-white rounded-4 p-5 border" style="border-style: dashed !important;">
                    <i class="fas fa-users-slash fa-3x text-muted opacity-25 mb-3"></i>
                    <h6 class="text-muted">Belum ada data network partner.</h6>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('partnerSearch');
    
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        // Target all rows in all bodies
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const textContent = row.innerText.toLowerCase();
            if (textContent.includes(term)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
