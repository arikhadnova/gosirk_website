<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / DESA PILOT</span>
        <h1 class="fw-bold mb-0">Kelola Desa Pilot</h1>
        <p class="text-muted small mb-0">Kelola daftar desa percontohan untuk halaman Implementasi Partner.</p>
    </div>
    <div>
        <!-- <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addVillageModal">
            <i class="fas fa-plus-circle me-2"></i> Tambah Desa
        </button> -->
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="row g-4 mt-2">
    <?php if (empty($villages)) : ?>
        <div class="col-12 text-center py-5">
            <div class="bg-white rounded-4 p-5 border" style="border-style: dashed !important;">
                <i class="fas fa-map-marked-alt fa-3x text-muted opacity-25 mb-3"></i>
                <h6 class="text-muted">Belum ada data desa pilot.</h6>
            </div>
        </div>
    <?php else : ?>
        <?php foreach ($villages as $v) : ?>
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative village-card">
                    <div class="position-relative">
                        <?php 
                            $img_src = $v->image;
                            if ($img_src && strpos($img_src, 'http') !== 0) {
                                $img_src = ASSETS_URL . 'img/' . $img_src;
                            }
                        ?>
                        <img src="<?= $img_src ?>" class="card-img-top" alt="<?= $v->name_id ?>" style="height: 180px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-2">
                            <button class="btn btn-sm btn-white shadow-sm rounded-circle edit-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editVillageModal"
                                    data-id="<?= $v->id ?>"
                                    data-name-id="<?= htmlspecialchars($v->name_id) ?>"
                                    data-order="<?= $v->order_priority ?>">
                                <i class="fas fa-edit text-primary"></i>
                            </button>
                            <!-- <a href="<?= BASE_URL ?>admin/pilot_villages_delete/<?= $v->id ?>" class="btn btn-sm btn-white shadow-sm rounded-circle" onclick="return confirm('Hapus desa ini?')">
                                <i class="fas fa-trash text-danger"></i>
                            </a> -->
                        </div>
                    </div>
                    <div class="card-body p-3 text-center">
                        <h6 class="fw-bold mb-1"><?= $v->name_id ?></h6>
                        <p class="text-muted extra-small mb-0"><?= $v->name_en ?></p>
                        <span class="badge bg-light text-muted mt-2">Order: <?= $v->order_priority ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addVillageModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>admin/pilot_villages_store" method="POST" enctype="multipart/form-data">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom p-4">
                    <h5 class="modal-title fw-bold">Tambah Desa Pilot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Desa</label>
                        <input type="text" name="name_id" class="form-control" placeholder="Contoh: Desa Bengkel" <?= FormRules::attrs('pilot_village', 'name_id', 'store') ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Foto Desa</label>
                        <input type="file" name="image" class="form-control" <?= FormRules::attrs('pilot_village', 'image', 'store') ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Urutan Tampilan</label>
                        <input type="number" name="order_priority" class="form-control" value="0" <?= FormRules::attrs('pilot_village', 'order_priority', 'store') ?>>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editVillageModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>admin/pilot_villages_update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom p-4">
                    <h5 class="modal-title fw-bold">Edit Desa Pilot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Desa</label>
                        <input type="text" name="name_id" id="edit-name-id" class="form-control" <?= FormRules::attrs('pilot_village', 'name_id', 'update') ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ganti Foto (Opsional)</label>
                        <input type="file" name="image" class="form-control" <?= FormRules::attrs('pilot_village', 'image', 'update') ?>>
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Urutan Tampilan</label>
                        <input type="number" name="order_priority" id="edit-order" class="form-control" <?= FormRules::attrs('pilot_village', 'order_priority', 'update') ?>>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.village-card { transition: all 0.3s ease; }
.village-card:hover { transform: translateY(-5px); shadow: 0 10px 20px rgba(0,0,0,0.1); }
.btn-white { background: white; border: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtns = document.querySelectorAll('.edit-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-name-id').value = this.dataset.nameId;
            document.getElementById('edit-order').value = this.dataset.order;
        });
    });
});
</script>
