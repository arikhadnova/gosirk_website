<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <span class="admin-header-badge d-inline-block text-uppercase">DASHBOARD / CAPACITY BUILDING / DAFTAR</span>
            <h1 class="fw-bold mb-0">Kelola Capacity Building</h1>
            <p class="text-muted small mb-0">Kelola layanan yang ditampilkan di halaman GoSirk Institute.</p>
        </div>
        <a href="<?= BASE_URL; ?>admin/gi_services_create" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Tambah Layanan GI
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 100px;">Gambar</th>
                    <th>Judul Layanan</th>
                    <th>Kategori</th>

                    <th class="text-center">Prioritas</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($services)) : ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">Belum ada layanan GI.</div>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($services as $s) : ?>
                        <tr>
                            <td class="ps-4">
                                <div class="rounded-3 overflow-hidden bg-light" style="width: 60px; height: 45px;">
                                    <?php if ($s->image) : ?>
                                        <img src="<?= ASSETS_URL; ?>img/gi/<?= $s->image; ?>" class="w-100 h-100 object-fit-cover">
                                    <?php else : ?>
                                        <div class="d-flex h-100 align-items-center justify-content-center text-muted">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= $s->title_id; ?></div>
                                <div class="extra-small text-muted"><?= $s->title_en; ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border-0 px-2"><?= ucfirst($s->category); ?></span>
                            </td>

                            <td class="text-center">
                                <?= $s->order_priority; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= BASE_URL; ?>gi/detail/<?= $s->slug; ?>" class="btn btn-sm btn-light text-info rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="View" target="_blank"><i class="fas fa-external-link-alt small"></i></a>
                                    <a href="<?= BASE_URL; ?>admin/gi_services/edit/<?= $s->id; ?>" class="btn btn-sm btn-light text-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Edit"><i class="fas fa-edit small"></i></a>
                                    <a href="<?= BASE_URL; ?>admin/gi_services_delete/<?= $s->id; ?>" class="btn btn-sm btn-light text-danger rounded-circle btn-delete-confirm" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Hapus"><i class="fas fa-trash small"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
