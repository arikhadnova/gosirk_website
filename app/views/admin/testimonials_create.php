<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / TESTIMONIALS / CREATE</span>
    <h1 class="fw-bold mb-0">Tambah Testimoni</h1>
    <p class="text-muted small">Tambahkan testimoni baru dari klien atau mitra.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/testimonials_store" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Pemberi Testimoni</label>
                            <input type="text" name="name" class="form-control bg-light border-0" placeholder="Contoh: Drs. Budi Santoso" <?= FormRules::attrs('testimonial', 'name', 'store') ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Status / Aktif</label>
                            <select name="status" class="form-select bg-light border-0" <?= FormRules::attrs('testimonial', 'status', 'store') ?>>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori Halaman</label>
                            <select name="page" class="form-select bg-light border-0" <?= FormRules::attrs('testimonial', 'page', 'store') ?>>

                                <option value="gi">Capacity Building (GI)</option>
                                <option value="implentasi_partner">Implementasi Partner</option>
                                <option value="konsultan">Konsultansi</option>
                                <option value="ggc">GoSirk Green Community</option>
                                <option value="go_ngompos_project">Go Ngompos Project</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jabatan</label>
                            <input type="text" name="role_id" class="form-control bg-light border-0" placeholder="Contoh: Kepala Desa" <?= FormRules::attrs('testimonial', 'role_id', 'store') ?>>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small">Testimoni</label>
                            <textarea name="content_id" class="form-control bg-light border-0" rows="4" placeholder="Tulis testimoni dalam bahasa Indonesia..." <?= FormRules::attrs('testimonial', 'content_id', 'store') ?>></textarea>
                        </div>
                        <div class="col-12">
                            <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan dibuat otomatis.</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small">Foto Profil (Optional)</label>
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            <div class="form-text extra-small">Format: JPG, PNG, JPEG. Max 2MB.</div>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Testimoni</button>
                        <a href="<?= BASE_URL ?>admin/testimonials" class="btn btn-light fw-bold px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
