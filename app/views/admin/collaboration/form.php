<?php
// Shared create/edit form for collaboration documents. Expects $data['doc'] (null when creating).
$doc = $data['doc'] ?? null;
$mode = $doc ? 'update' : 'store';
$autoSend = $doc ? ((int) ($doc->auto_send ?? 1)) === 1 : true;
$currentFile = $doc ? realpath(dirname(__DIR__, 3) . '/storage/documents/' . $doc->file_path) : false;
$currentSize = $currentFile ? round(filesize($currentFile) / 1048576, 2) . ' MB' : null;
?>
<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/collaboration" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block">DASHBOARD / DOKUMEN / <?= $doc ? 'EDIT' : 'TAMBAH' ?></span>
            <h1 class="fw-bold mb-0"><?= $doc ? 'Edit Dokumen' : 'Tambah Dokumen' ?></h1>
            <p class="text-muted small mb-0">Executive Summary, Company Profile, atau Concept Note yang dikirim ke pengunjung lewat email.</p>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/<?= $doc ? 'collaboration_update' : 'collaboration_store' ?>" method="POST" enctype="multipart/form-data">
    <?php if ($doc) : ?>
        <input type="hidden" name="id" value="<?= (int) $doc->id; ?>">
        <input type="hidden" name="old_file" value="<?= htmlspecialchars($doc->file_path); ?>">
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-file-alt me-2"></i>Informasi Dokumen</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Judul Dokumen</label>
                        <input type="text" name="title_id" class="form-control form-control-lg" placeholder="Contoh: Concept Note Go Ngompos 2026"
                               value="<?= htmlspecialchars($doc->title_id ?? '') ?>" <?= FormRules::attrs('collaboration', 'title_id', $mode) ?>><?= EnField::render('title', $doc->title_id ?? '', $doc->title_en ?? '', 'text', 1) ?>
                        <small class="text-muted extra-small d-block mt-1"><i class="fas fa-magic me-1"></i> Judul tampil di halaman publik dan di email. Versi Bahasa Inggris: pilih EN di header halaman (kosong = diterjemahkan otomatis).</small>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Tipe Dokumen</label>
                            <select name="type" class="form-select" <?= FormRules::attrs('collaboration', 'type', $mode) ?>>
                                <?php foreach (Collaboration_model::DOC_TYPES as $value => $label) : ?>
                                    <option value="<?= $value ?>" <?= ($doc->type ?? '') === $value ? 'selected' : ''; ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted extra-small d-block mt-1">Menentukan di halaman mana dokumen ditawarkan.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Status</label>
                            <select name="status" class="form-select" <?= FormRules::attrs('collaboration', 'status', $mode) ?>>
                                <option value="active" <?= ($doc->status ?? 'active') === 'active' ? 'selected' : ''; ?>>Active (Tampil)</option>
                                <option value="inactive" <?= ($doc->status ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-file-pdf me-2"></i>File Dokumen</h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($doc) : ?>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-3">
                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                            <div class="flex-grow-1 min-w-0">
                                <div class="small fw-bold text-dark">File saat ini</div>
                                <div class="small text-muted text-truncate"><?= htmlspecialchars($doc->file_path) ?><?= $currentSize ? ' &middot; ' . $currentSize : ' &middot; <span class="text-danger">file tidak ditemukan di server</span>' ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <label class="d-block p-4 text-center rounded-3 border border-2" style="border-style: dashed !important; cursor: pointer;">
                        <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2 d-block"></i>
                        <span class="fw-bold text-dark small d-block mb-2"><?= $doc ? 'Ganti file (opsional)' : 'Pilih file PDF' ?></span>
                        <input type="file" name="document" class="form-control" accept=".pdf" <?= FormRules::attrs('collaboration', 'document', $mode) ?>>
                        <small class="text-muted extra-small d-block mt-2">Format PDF, maksimal <?= round(Upload::maxBytes('pdf') / 1048576, 1) ?> MB. File disimpan di folder aman dan tidak bisa diakses langsung oleh publik.</small>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-dark">Pengiriman</h5>
                </div>
                <div class="card-body p-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="auto_send" id="autoSend" <?= $autoSend ? 'checked' : ''; ?>>
                        <label class="form-check-label fw-bold small" for="autoSend">Kirim otomatis ke email pemohon</label>
                    </div>
                    <ul class="small text-muted mt-3 mb-0 ps-3">
                        <li class="mb-1"><b>Aktif:</b> PDF langsung dikirim sebagai lampiran saat pengunjung mengisi form.</li>
                        <li><b>Nonaktif:</b> permintaan dicatat dan admin mendapat notifikasi untuk mengirim secara manual.</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>admin/email_settings" class="small d-inline-block mt-3 text-decoration-none"><i class="fas fa-envelope me-1"></i> Atur isi email</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> <?= $doc ? 'Perbarui Dokumen' : 'Simpan Dokumen' ?>
                    </button>
                    <a href="<?= BASE_URL; ?>admin/collaboration" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small">Batal dan Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>
