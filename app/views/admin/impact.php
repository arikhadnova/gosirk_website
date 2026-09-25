<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / DATA DAMPAK</span>
        <h1 class="fw-bold mb-0">Kelola Data Dampak</h1>
        <p class="text-muted small mb-0">Kelola metrik dampak. Tersedia 4 slot tetap per halaman yang dapat diperbarui.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<?php 
$page_names = [
    'home' => 'Home Page',
    'gi' => 'GoSirk Institute',
    'ggc' => 'GoSirk Green Community',
    'go_ngompos_project' => 'Go Ngompos Project',
    'clocc' => 'CLOCC Impact'
];
$display_name = $page_names[$page_target] ?? 'Impact Data';
?>

<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0 text-primary"><?= $display_name ?> Metrik</h5>
            <small class="text-muted"><?= count($impacts) ?> metrik tersedia di halaman ini</small>
        </div>
        <a href="<?= BASE_URL; ?>admin/impact/create?page=<?= $page_target ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="fas fa-plus me-1"></i> Tambah Metrik
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive table-scroll-container" style="max-height: 600px; overflow-y: auto;">
            <table class="table align-middle mb-0">
                <thead class="bg-light sticky-top">
                    <tr>
                        <th class="ps-4" style="width: 150px;">Section</th>
                        <th>Label</th>
                        <th class="text-center">Value</th>
                        <th>Note</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($impacts as $imp) : ?>
                        <tr class="hover-bg-light">
                            <td class="ps-4">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill extra-small">
                                    <?= $imp->section; ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small"><?= $imp->label_id; ?></div>
                                <div class="text-muted extra-small"><?= $imp->label_en; ?></div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                                    <?= $imp->value; ?> <?= $imp->unit; ?>
                                </span>
                            </td>
                            <td>
                                <div class="text-muted extra-small text-truncate" style="max-width: 250px;" title="<?= $imp->note_id ?>">
                                    <?= $imp->note_id ?: '-'; ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= BASE_URL; ?>admin/impact/edit/<?= $imp->id; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL; ?>admin/impact_delete/<?= $imp->id; ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Hapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if (count($impacts) == 0) : ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted small">
                                <i class="fas fa-inbox d-block mb-3 fs-3 opacity-25"></i>
                                Belum ada data untuk halaman ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.hover-bg-light:hover { background-color: rgba(0,0,0,0.015); }
.extra-small { font-size: 11px; }
.stat-icon-box {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}
.stat-icon-blue {
    background: rgba(41, 180, 113, 0.1);
    color: #29b471;
}
</style>
