<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / FAQ / TAMBAH</span>
    <h1 class="fw-bold mb-0">Tambah FAQ</h1>
    <p class="text-muted small">Tambahkan pertanyaan sering diajukan untuk kategori tertentu.</p>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/faqs_store" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori Halaman</label>
                            <select name="page" class="form-select bg-light border-0" <?= FormRules::attrs('faq', 'page', 'store') ?>>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="gi">Capacity Building (GI)</option>
                                <option value="implentasi_partner">Implementasi Partner</option>
                                <option value="konsultan">Konsultansi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Status</label>
                            <select name="status" class="form-select bg-light border-0">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Urutan (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control bg-light border-0" value="0" <?= FormRules::attrs('faq', 'sort_order', 'store') ?>>
                        </div>
                        
                        <div class="col-12 border-top pt-3 mt-4">
                            <h6 class="fw-bold text-primary mb-3">Konten FAQ</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold extra-small">Pertanyaan</label>
                                <input type="text" name="question_id" class="form-control bg-light border-0" placeholder="Contoh: Apa itu GoSirk?" <?= FormRules::attrs('faq', 'question_id', 'store') ?>>
                            </div>
                            <div>
                                <label class="form-label fw-bold extra-small">Jawaban</label>
                                <textarea name="answer_id" class="form-control bg-light border-0" rows="4" placeholder="Tulis jawaban lengkap dalam bahasa Indonesia..." <?= FormRules::attrs('faq', 'answer_id', 'store') ?>></textarea>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted"><i class="fas fa-magic me-1"></i> Versi Bahasa Inggris akan dibuat otomatis.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4">Simpan FAQ</button>
                        <a href="<?= BASE_URL ?>admin/faqs" class="btn btn-light fw-bold px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
