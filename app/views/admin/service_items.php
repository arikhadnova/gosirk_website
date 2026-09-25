<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= BASE_URL; ?>admin/services" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <span class="admin-header-badge d-inline-block text-uppercase">DASHBOARD / LAYANAN / <?= $data['category'] ?></span>
                <h1 class="fw-bold mb-0"><?= $data['title'] ?></h1>
            </div>
        </div>
        <a href="<?= BASE_URL; ?>admin/service_item_create/<?= $data['category'] ?>" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Tambah Item Baru
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 60px;">#</th>
                    <th style="width: 80px;">Ikon</th>
                    <th>Judul Item</th>
                    <th>Link / URL</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['items'])) : ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                <p>Belum ada item dalam kategori ini.</p>
                            </div>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($data['items'] as $index => $item) : ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $index + 1 ?></td>
                            <td>
                                <div class="stat-icon-box stat-icon-blue m-0" style="width: 40px; height: 40px;">
                                    <i class="fas fa-<?= $item->icon ?: 'circle' ?> fs-6"></i>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small"><?= $item->title_id ?></div>
                                <div class="text-muted extra-small"><?= $item->title_en ?></div>
                            </td>
                            <td class="small">
                                <span class="badge bg-light text-muted border-0 fw-normal">/<?= $item->link_url ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= BASE_URL; ?>admin/service_item_edit/<?= $item->id; ?>" class="btn btn-sm btn-light text-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Edit"><i class="fas fa-edit small"></i></a>
                                    <a href="<?= BASE_URL; ?>admin/service_item_delete/<?= $item->id; ?>" class="btn btn-sm btn-light text-danger rounded-circle btn-delete-confirm" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Hapus"><i class="fas fa-trash small"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

