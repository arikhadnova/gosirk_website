<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / GGC ACTIONS</span>
        <h1 class="fw-bold mb-0">Manajemen Aksi GGC</h1>
        <p class="text-muted small mb-0">Kelola konten "Aksi Kami" di halaman GoSirk Green Community (GGC).</p>
    </div>
    <div>
        <!-- <button type="button" class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addActionModal">
            <i class="fas fa-plus-circle me-2"></i> Tambah Aksi
        </button> -->
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="row g-4 mt-2">
    <?php if (empty($actions)) : ?>
        <div class="col-12 text-center py-5">
            <div class="bg-white rounded-4 p-5 border" style="border-style: dashed !important;">
                <i class="fas fa-hiking fa-3x text-muted opacity-25 mb-3"></i>
                <h6 class="text-muted">Belum ada data aksi GGC.</h6>
            </div>
        </div>
    <?php else : ?>
        <?php foreach ($actions as $a) : ?>
            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden gallery-card">
                    <div class="position-relative">
                        <?php 
                            $img_src = $a->image;
                            if ($img_src && strpos($img_src, 'http') !== 0) {
                                $img_src = ASSETS_URL . 'img/' . $img_src;
                            }
                        ?>
                        <img src="<?= $img_src ?>" class="card-img-top" alt="<?= $a->title_id ?>" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-2">
                            <button class="btn btn-sm btn-white shadow-sm rounded-circle edit-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editActionModal"
                                    data-id="<?= $a->id ?>"
                                    data-title-id="<?= htmlspecialchars($a->title_id) ?>"
                                    data-desc-id="<?= htmlspecialchars($a->description_id) ?>"
                                    data-order="<?= $a->order_priority ?>">
                                <i class="fas fa-edit text-primary"></i>
                            </button>
                            <!-- <a href="<?= BASE_URL ?>admin/ggc_actions_delete/<?= $a->id ?>" class="btn btn-sm btn-white shadow-sm rounded-circle" onclick="return confirm('Hapus aksi ini?')">
                                <i class="fas fa-trash text-danger"></i>
                            </a> -->
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-2 text-success"><?= $a->title_id ?></h6>
                        <p class="text-muted small mb-3 line-clamp-2"><?= $a->description_id ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="badge bg-light text-muted">Order: <?= $a->order_priority ?></span>
                            <!-- <small class="text-muted italic"><?= $a->title_en ?></small> -->
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addActionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= BASE_URL ?>admin/ggc_actions_store" method="POST" enctype="multipart/form-data">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom p-4">
                    <h5 class="modal-title fw-bold text-success">Tambah Aksi GGC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Judul Aksi</label>
                            <input type="text" name="title_id" class="form-control" required placeholder="Aksi Tanam Mangrove">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Deskripsi</label>
                            <textarea name="description_id" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Foto Aksi</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Urutan</label>
                            <input type="number" name="order_priority" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editActionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= BASE_URL ?>admin/ggc_actions_update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom p-4">
                    <h5 class="modal-title fw-bold text-success">Edit Aksi GGC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Judul Aksi</label>
                            <input type="text" name="title_id" id="edit-title-id" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Deskripsi</label>
                            <textarea name="description_id" id="edit-desc-id" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Ganti Foto (Opsional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Urutan</label>
                            <input type="number" name="order_priority" id="edit-order" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.gallery-card { transition: all 0.3s ease; }
.gallery-card:hover { transform: translateY(-5px); }
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.btn-white { background: white; border: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtns = document.querySelectorAll('.edit-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-title-id').value = this.dataset.titleId;
            document.getElementById('edit-desc-id').value = this.dataset.descId;
            document.getElementById('edit-order').value = this.dataset.order;
        });
    });
});
</script>
