<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / TESTIMONI / EDIT</span>
    <h1 class="fw-bold mb-0">Edit Testimoni</h1>
    <p class="text-muted small">Perbarui data testimoni terpilih.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/testimonials_update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $testimonial->id ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Pemberi Testimoni</label>
                            <input type="text" name="name" class="form-control bg-light border-0" value="<?= $testimonial->name ?>" <?= FormRules::attrs('testimonial', 'name', 'update') ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Status / Aktif</label>
                            <select name="status" class="form-select bg-light border-0" <?= FormRules::attrs('testimonial', 'status', 'update') ?>>
                                <option value="active" <?= $testimonial->status == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $testimonial->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori Halaman</label>
                            <select name="page" class="form-select bg-light border-0" <?= FormRules::attrs('testimonial', 'page', 'update') ?>>

                                <option value="gi" <?= $testimonial->page == 'gi' ? 'selected' : '' ?>>Capacity Building (GI)</option>
                                <option value="implentasi_partner" <?= $testimonial->page == 'implentasi_partner' ? 'selected' : '' ?>>Implementasi Partner</option>
                                <option value="konsultan" <?= $testimonial->page == 'konsultan' ? 'selected' : '' ?>>Konsultansi</option>
                                <option value="ggc" <?= $testimonial->page == 'ggc' ? 'selected' : '' ?>>GoSirk Green Community</option>
                                <option value="go_ngompos_project" <?= $testimonial->page == 'go_ngompos_project' ? 'selected' : '' ?>>Go Ngompos Project</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jabatan</label>
                            <input type="text" name="role_id" class="form-control bg-light border-0" value="<?= $testimonial->role_id ?>" <?= FormRules::attrs('testimonial', 'role_id', 'update') ?>>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small">Testimoni</label>
                            <textarea name="content_id" class="form-control bg-light border-0" rows="4" <?= FormRules::attrs('testimonial', 'content_id', 'update') ?>><?= $testimonial->content_id ?></textarea>
                        </div>
                        <div class="col-12">
                            <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan diperbarui otomatis saat disimpan.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Foto Saat Ini</label>
                            <div class="rounded-3 bg-light overflow-hidden mb-2" style="width: 100px; height: 100px;">
                                <?php if ($testimonial->image) : ?>
                                    <img src="<?= ASSETS_URL . 'img/testimonials/' . $testimonial->image ?>" class="w-100 h-100 object-fit-cover">
                                <?php else : ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-muted opacity-25 fa-2x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold small">Ganti Foto Profil (Optional)</label>
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            <div class="form-text extra-small">Format: JPG, PNG, JPEG. Max 2MB. Abaikan jika tidak ingin mengganti.</div>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4">Perbarui Testimoni</button>
                        <a href="<?= BASE_URL ?>admin/testimonials" class="btn btn-light fw-bold px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
