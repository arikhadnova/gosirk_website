<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / KOLABORASI</span>
        <h1 class="fw-bold mb-0">Dokumen Kolaborasi</h1>
        <p class="text-muted small mb-0">Kelola Executive Summary dan Company Profile yang bersifat rahasia.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL; ?>admin/collaboration_requests" class="btn btn-light text-primary fw-bold px-3">
            <i class="fas fa-history me-2"></i> Log Permintaan
        </a>
        <a href="<?= BASE_URL; ?>admin/collaboration_create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Dokumen
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="table-responsive mt-4">
    <table class="table align-middle">
        <thead>
            <tr>
                <th style="width: 80px;">Icon</th>
                <th>Judul Dokumen</th>
                <th>Tipe</th>
                <th class="text-center">Status</th>
                <th class="text-center">Pengiriman</th>
                <th>Terdaftar</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($docs)) : ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">Belum ada dokumen kolaborasi. Klik khusus "Tambah Dokumen" untuk memulai.</div>
                    </td>
                </tr>
            <?php else : ?>
                <?php foreach ($docs as $doc) : ?>
                    <tr class="shadow-sm">
                        <td>
                            <div class="stat-icon-box stat-icon-orange m-0" style="width: 45px; height: 45px;">
                                <i class="fas fa-file-shield fs-6 text-warning"></i>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small"><?= $doc->title_id; ?></div>
                            <div class="text-muted extra-small"><?= $doc->title_en; ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-muted border-0 extra-small px-3">
                                <?= Collaboration_model::DOC_TYPES[$doc->type] ?? $doc->type; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $doc->status == 'active' ? 'bg-success' : 'bg-danger'; ?> bg-opacity-10 <?= $doc->status == 'active' ? 'text-success' : 'text-danger'; ?> px-3 rounded-pill extra-small">
                                <?= ucfirst($doc->status); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if (((int) ($doc->auto_send ?? 1)) === 1) : ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill extra-small"><i class="fas fa-paper-plane me-1"></i> Otomatis</span>
                            <?php else : ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 rounded-pill extra-small"><i class="fas fa-user-check me-1"></i> Manual</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="text-muted extra-small"><?= date('d M Y', strtotime($doc->created_at)); ?></span></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= BASE_URL; ?>admin/collaboration_edit/<?= $doc->id; ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL; ?>admin/collaboration_delete/<?= $doc->id; ?>" class="btn btn-sm btn-light text-danger btn-delete-confirm" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
