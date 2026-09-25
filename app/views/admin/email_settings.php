<?php
$c = $data['config'];
$source = [
    'admin' => '<span class="badge bg-success bg-opacity-10 text-success">Tersimpan di admin</span>',
    'env'   => '<span class="badge bg-secondary bg-opacity-10 text-secondary">Dari file .env</span>',
    'none'  => '<span class="badge bg-danger bg-opacity-10 text-danger">Belum diisi</span>',
][$data['key_source']];
?>
<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / SISTEM / EMAIL</span>
    <h1 class="fw-bold mb-0">Pengaturan Email</h1>
    <p class="text-muted small mb-0">Pengaturan pengiriman email lewat Brevo: pengiriman dokumen ke pengunjung dan notifikasi ke admin.</p>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 760px;">
    <div class="card-body p-4">
        <form action="<?= BASE_URL; ?>admin/update_email_settings" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Email Pengirim</label>
                    <input type="email" name="mail_from" class="form-control" value="<?= htmlspecialchars($c['from']) ?>" <?= FormRules::attrs('email_settings', 'mail_from', 'update') ?>>
                    <small class="text-muted extra-small d-block mt-1">Harus sudah diverifikasi sebagai sender di Brevo.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Nama Pengirim</label>
                    <input type="text" name="mail_from_name" class="form-control" value="<?= htmlspecialchars($c['from_name']) ?>" <?= FormRules::attrs('email_settings', 'mail_from_name', 'update') ?>>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Email Admin (Notifikasi)</label>
                    <input type="email" name="mail_admin_address" class="form-control" value="<?= htmlspecialchars($c['admin']) ?>" <?= FormRules::attrs('email_settings', 'mail_admin_address', 'update') ?>>
                    <small class="text-muted extra-small d-block mt-1">Menerima notifikasi pesan kontak dan permintaan dokumen.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark d-flex align-items-center gap-2">API Key Brevo <?= $source ?></label>
                    <input type="password" name="mail_brevo_api_key" class="form-control" autocomplete="new-password"
                           placeholder="<?= $data['key_hint'] ? htmlspecialchars($data['key_hint']) : 'xkeysib-...' ?>" <?= FormRules::attrs('email_settings', 'mail_brevo_api_key', 'update') ?>>
                    <small class="text-muted extra-small d-block mt-1">Kosongkan jika tidak ingin mengganti key. Buat key di Brevo: SMTP &amp; API &rarr; API Keys.</small>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="fas fa-save me-2"></i> Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4" style="max-width: 760px;">
    <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="fw-bold text-dark">Uji pengiriman</div>
            <small class="text-muted">"Cek Koneksi" hanya memeriksa key dan sender tanpa mengirim email. "Kirim Email Tes" mengirim satu email ke <?= htmlspecialchars($c['admin']) ?>.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL; ?>admin/email_settings_check" class="btn btn-outline-primary rounded-pill px-3"><i class="fas fa-plug me-1"></i> Cek Koneksi</a>
            <form action="<?= BASE_URL; ?>admin/email_settings_test" method="POST" class="m-0">
                <button type="submit" class="btn btn-outline-secondary rounded-pill px-3"><i class="fas fa-paper-plane me-1"></i> Kirim Email Tes</button>
            </form>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-4" id="templates" style="max-width: 960px;">
    <div class="card-header bg-white border-bottom p-4">
        <h5 class="fw-bold mb-1 text-dark">Template Email</h5>
        <small class="text-muted">Tulis sebagai teks biasa. Baris baru tetap dipertahankan, <code>**teks**</code> menjadi huruf tebal, dan placeholder seperti <code>{nama}</code> diganti otomatis. Kosongkan untuk memakai teks bawaan.</small>
    </div>
    <div class="card-body p-4">
        <form action="<?= BASE_URL; ?>admin/update_email_templates" method="POST">
            <ul class="nav nav-pills gap-2 mb-4" role="tablist">
                <?php $first = true; foreach (Mail::TEMPLATES as $key => $t) : ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill small <?= $first ? 'active' : '' ?>" data-bs-toggle="pill" data-bs-target="#tpl-<?= $key ?>" type="button" role="tab"><?= htmlspecialchars($t['label']) ?></button>
                    </li>
                <?php $first = false; endforeach; ?>
            </ul>
            <div class="tab-content">
                <?php $first = true; foreach (Mail::TEMPLATES as $key => $t) : $cur = Mail::template($key); ?>
                    <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="tpl-<?= $key ?>" role="tabpanel">
                        <p class="small text-muted mb-3"><i class="fas fa-info-circle me-1"></i> <?= htmlspecialchars($t['hint']) ?></p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Subjek</label>
                            <input type="text" name="mail_tpl_<?= $key ?>_subject" class="form-control tpl-field" value="<?= htmlspecialchars($cur['subject']) ?>"
                                   data-default="<?= htmlspecialchars($t['subject']) ?>" <?= FormRules::attrs('email_templates', "mail_tpl_{$key}_subject", 'update') ?>>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Isi Email</label>
                            <textarea name="mail_tpl_<?= $key ?>_body" rows="9" class="form-control tpl-field font-monospace small"
                                      data-default="<?= htmlspecialchars($t['body']) ?>" <?= FormRules::attrs('email_templates', "mail_tpl_{$key}_body", 'update') ?>><?= htmlspecialchars($cur['body']) ?></textarea>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <small class="text-muted me-1">Sisipkan:</small>
                            <?php foreach ($t['placeholders'] as $ph) : ?>
                                <button type="button" class="btn btn-sm btn-light border rounded-pill py-0 px-2 insert-ph" data-ph="{<?= $ph ?>}"><code class="small">{<?= $ph ?>}</code></button>
                            <?php endforeach; ?>
                            <button type="button" class="btn btn-sm btn-link text-muted ms-auto reset-tpl"><i class="fas fa-undo me-1"></i> Kembalikan ke bawaan</button>
                        </div>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>
            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="fas fa-save me-2"></i> Simpan Template</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let lastField = null;
    document.querySelectorAll('.tpl-field').forEach((el) => el.addEventListener('focus', () => { lastField = el; }));

    // Insert a placeholder at the cursor of the last focused field in the same tab (default: the body)
    document.querySelectorAll('.insert-ph').forEach((btn) => {
        // Keep the cursor in the field being edited when the chip is pressed
        btn.addEventListener('mousedown', (e) => e.preventDefault());
        btn.addEventListener('click', () => {
        const pane = btn.closest('.tab-pane');
        const active = document.activeElement;
        const field = active && active.classList.contains('tpl-field') && pane.contains(active) ? active
            : (lastField && pane.contains(lastField) ? lastField : pane.querySelector('textarea'));
        const start = field.selectionStart ?? field.value.length, end = field.selectionEnd ?? field.value.length;
        field.value = field.value.slice(0, start) + btn.dataset.ph + field.value.slice(end);
        field.focus();
        field.selectionStart = field.selectionEnd = start + btn.dataset.ph.length;
        });
    });

    document.querySelectorAll('.reset-tpl').forEach((btn) => btn.addEventListener('click', () => {
        btn.closest('.tab-pane').querySelectorAll('.tpl-field').forEach((el) => { el.value = el.dataset.default; });
    }));
});
</script>
