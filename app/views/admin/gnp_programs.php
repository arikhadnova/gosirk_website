<?php
$programs = $data['programs'];
$section = $data['section'] ?? null;
$sectionActive = !$section || (int) $section->is_active === 1;

// Form fields shared by the add and edit modals
$programFields = function ($mode) {
    $p = $mode === 'store' ? 'add' : 'edit';
    ?>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-bold small text-dark">Judul Program</label>
            <input type="text" name="title_id" id="<?= $p ?>Title" class="form-control" placeholder="Contoh: Kelas Ngompos" <?= FormRules::attrs('gnp_program', 'title_id', $mode) ?>>
        </div>
        <div class="col-12">
            <label class="form-label fw-bold small text-dark">Deskripsi</label>
            <textarea name="description_id" id="<?= $p ?>Desc" rows="3" class="form-control" placeholder="Penjelasan singkat program..." <?= FormRules::attrs('gnp_program', 'description_id', $mode) ?>></textarea>
        </div>
        <div class="col-md-5">
            <label class="form-label fw-bold small text-dark">Label</label>
            <input type="text" name="badge_id" id="<?= $p ?>Badge" class="form-control" placeholder="Contoh: Edukasi" <?= FormRules::attrs('gnp_program', 'badge_id', $mode) ?>>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold small text-dark">Warna Label</label>
            <select name="badge_color" id="<?= $p ?>Color" class="form-select" <?= FormRules::attrs('gnp_program', 'badge_color', $mode) ?>>
                <?php foreach (GnpProgram_model::BADGE_COLORS as $key => [$label]) : ?>
                    <option value="<?= $key ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold small text-dark">Urutan</label>
            <input type="number" name="order_priority" id="<?= $p ?>Order" class="form-control" value="0" <?= FormRules::attrs('gnp_program', 'order_priority', $mode) ?>>
        </div>
        <div class="col-12">
            <label class="form-label fw-bold small text-dark">Foto</label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" <?= FormRules::attrs('gnp_program', 'image', $mode) ?>>
            <small class="text-muted extra-small d-block mt-1"><?= $mode === 'store' ? 'Format JPG, PNG, atau WEBP. Disarankan rasio lanskap (4:3).' : 'Kosongkan jika tidak ingin mengganti foto.' ?></small>
        </div>
    </div>
    <small class="text-muted d-block mt-3"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris (judul, deskripsi, label) dibuat otomatis saat disimpan.</small>
    <?php
};
?>
<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / GO NGOMPOS / PROGRAM</span>
        <h1 class="fw-bold mb-0">Program Go Ngompos</h1>
        <p class="text-muted small mb-0">Kartu di section "Program Utama" halaman Go Ngompos Project.</p>
    </div>
    <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addProgramModal">
        <i class="fas fa-plus-circle me-2"></i> Tambah Program
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="<?= BASE_URL; ?>admin/gnp_programs_section" method="POST">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Judul Section</label>
                    <input type="text" name="title_id" class="form-control" placeholder="PROGRAM UTAMA" value="<?= htmlspecialchars($section->title_id ?? '') ?>" <?= FormRules::attrs('gnp_program_section', 'title_id', 'update') ?>>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold small text-dark">Subjudul</label>
                    <input type="text" name="content_id" class="form-control" placeholder="Langkah praktis untuk membangun kebiasaan ngompos yang konsisten." value="<?= htmlspecialchars($section->content_id ?? '') ?>" <?= FormRules::attrs('gnp_program_section', 'content_id', 'update') ?>>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-between gap-2">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="is_active" id="programsActive" <?= $sectionActive ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="programsActive">Tampilkan</label>
                    </div>
                    <button type="submit" class="btn btn-outline-primary rounded-pill px-3 btn-sm">Simpan</button>
                </div>
            </div>
            <small class="text-muted extra-small d-block mt-2">Kosongkan judul/subjudul untuk memakai teks bawaan. Versi Bahasa Inggris dibuat otomatis.</small>
        </form>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($programs)) : ?>
        <div class="col-12">
            <div class="bg-white rounded-4 p-5 text-center border" style="border-style: dashed !important;">
                <i class="fas fa-seedling fa-3x text-muted opacity-25 mb-3"></i>
                <h6 class="text-muted mb-0">Belum ada program. Klik "Tambah Program".</h6>
            </div>
        </div>
    <?php endif; ?>
    <?php foreach ($programs as $p) : [, $badgeStyle] = GnpProgram_model::BADGE_COLORS[$p->badge_color] ?? GnpProgram_model::BADGE_COLORS['success']; ?>
        <div class="col-xl-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="position-relative">
                    <?php if ($p->image) : ?>
                        <img src="<?= htmlspecialchars(GnpProgram_model::imageUrl($p->image)) ?>" class="w-100" alt="" style="height: 190px; object-fit: cover;">
                    <?php else : ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 190px;"><i class="fas fa-image fa-2x text-muted opacity-25"></i></div>
                    <?php endif; ?>
                    <?php if ($p->badge_id) : ?>
                        <span class="position-absolute top-0 start-0 m-3 px-3 py-1 text-white rounded-pill small fw-bold" style="<?= $badgeStyle ?>"><?= htmlspecialchars($p->badge_id) ?></span>
                    <?php endif; ?>
                    <?php if (preg_match('#^https?://#i', (string) $p->image)) : ?>
                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 fw-normal" title="Masih memakai foto contoh dari internet">Foto contoh</span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <h6 class="fw-bold mb-2 text-dark"><?= htmlspecialchars($p->title_id) ?></h6>
                    <p class="text-muted small mb-3 flex-grow-1"><?= htmlspecialchars($p->description_id) ?></p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <span class="badge bg-light text-muted">Urutan: <?= (int) $p->order_priority ?></span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light text-primary btn-icon btn-edit-program" title="Edit"
                                    data-id="<?= (int) $p->id ?>" data-title="<?= htmlspecialchars($p->title_id) ?>" data-desc="<?= htmlspecialchars($p->description_id) ?>"
                                    data-badge="<?= htmlspecialchars($p->badge_id) ?>" data-color="<?= htmlspecialchars($p->badge_color) ?>" data-order="<?= (int) $p->order_priority ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="<?= BASE_URL; ?>admin/gnp_programs_delete/<?= (int) $p->id ?>" class="btn btn-light text-danger btn-icon btn-delete-confirm" title="Hapus"
                               data-confirm-message="Program &quot;<?= htmlspecialchars($p->title_id) ?>&quot; akan dihapus permanen."><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="addProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form action="<?= BASE_URL; ?>admin/gnp_programs_store" method="POST" enctype="multipart/form-data">
                <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Program</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4"><?php $programFields('store'); ?></div>
                <div class="modal-footer"><button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fas fa-save me-2"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form action="<?= BASE_URL; ?>admin/gnp_programs_update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editId">
                <div class="modal-header"><h5 class="modal-title fw-bold">Edit Program</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4"><?php $programFields('update'); ?></div>
                <div class="modal-footer"><button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fas fa-save me-2"></i>Perbarui</button></div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('editProgramModal'));
    document.querySelectorAll('.btn-edit-program').forEach((btn) => btn.addEventListener('click', () => {
        const d = btn.dataset;
        document.getElementById('editId').value = d.id;
        document.getElementById('editTitle').value = d.title;
        document.getElementById('editDesc').value = d.desc;
        document.getElementById('editBadge').value = d.badge;
        document.getElementById('editColor').value = d.color;
        document.getElementById('editOrder').value = d.order;
        modal.show();
    }));
});
</script>
