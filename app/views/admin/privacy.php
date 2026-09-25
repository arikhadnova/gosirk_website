<?php $updated = PrivacyPolicy::formatDate($data['updated_at'], 'id'); ?>
<style>
    .privacy-editor .ck-editor__editable { min-height: 420px; }
    .privacy-editor .ck-content h2 { font-size: 1.15rem; font-weight: 700; }
    .privacy-editor .ck-content h3 { font-size: 1.05rem; font-weight: 700; }
</style>

<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">PENGATURAN / SITUS / KEBIJAKAN PRIVASI</span>
    <h1 class="fw-bold mb-0">Kebijakan Privasi</h1>
    <p class="text-muted small mb-0">Isi lengkap halaman Kebijakan Privasi. Gunakan tombol di toolbar untuk judul bagian, teks tebal, daftar, dan link. Terakhir diperbarui: <strong><?= $updated ?></strong> (tanggal ini ikut berubah otomatis saat disimpan).</p>
</div>

<form action="<?= BASE_URL; ?>admin/privacy_update" method="POST" class="privacy-editor">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs border-0 px-4 pt-3" role="tablist">
                <li class="nav-item"><button class="nav-link active fw-bold border-0 bg-transparent py-3 px-4" data-bs-toggle="tab" data-bs-target="#privacy-id" type="button">Bahasa Indonesia</button></li>
                <li class="nav-item"><button class="nav-link fw-bold border-0 bg-transparent py-3 px-4" data-bs-toggle="tab" data-bs-target="#privacy-en" type="button">English</button></li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="privacy-id">
                    <textarea name="content_id" id="privacyContentId"><?= htmlspecialchars($data['content_id']) ?></textarea>
                </div>
                <div class="tab-pane fade" id="privacy-en">
                    <textarea name="content_en" id="privacyContentEn"><?= htmlspecialchars($data['content_en']) ?></textarea>
                </div>
            </div>
            <p class="small text-muted mt-3 mb-0"><i class="fas fa-envelope me-1"></i>Email kontak di bawah halaman diambil otomatis dari Pengaturan › Situs › Header &amp; Footer.</p>
        </div>
    </div>
    <div class="position-sticky bottom-0 py-3" style="z-index: 5;">
        <div class="card border-0 shadow">
            <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between gap-3">
                <a href="<?= BASE_URL ?>privacy" target="_blank" class="small text-decoration-none"><i class="fas fa-external-link-alt me-1"></i>Lihat halaman</a>
                <button type="submit" class="btn btn-primary btn-action px-4"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    ['privacyContentId', 'privacyContentEn'].forEach((id) => {
        ClassicEditor.create(document.getElementById(id), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', '|', 'undo', 'redo'],
            heading: { options: [
                { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Judul bagian', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Sub-judul', class: 'ck-heading_heading3' },
            ] },
        }).catch((e) => console.error(e));
    });
</script>
