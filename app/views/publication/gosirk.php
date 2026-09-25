<!-- Custom CSS for Publication Page -->
<style>
    .pub-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .pub-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
        border-color: var(--bs-primary);
    }
    .pub-cover-container {
        height: 280px;
        background-color: #f8f9fa;
        position: relative;
        overflow: hidden;
    }
    .pub-cover-icon {
        font-size: 80px;
        color: #adb5bd;
        opacity: 0.5;
    }
</style>

<section class="section py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge bg-primary text-white rounded-pill px-3 py-2 mb-3 fw-bold" data-i18n="publication.header.badge">KNOWLEDGE HUB</span>
            <h2 class="fw-bold display-5 mb-3" data-i18n="publication.gosirk_title">GoSirk Publications</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;" data-i18n="publication.gosirk.desc">
                Temukan berbagai publikasi resmi, laporan, dan panduan dari GoSirk.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-pill overflow-hidden search-container">
                    <div class="card-body p-1">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent ps-4">
                                <span class="material-symbols-outlined text-muted">search</span>
                            </span>
                            <input type="text" class="form-control border-0 py-3 shadow-none bg-transparent" placeholder="Cari publikasi atau laporan..." id="pubSearch" data-i18n="publication.gosirk.search_placeholder">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4" id="pubGrid">
            <?php if (!empty($publications)) : ?>
                <?php foreach ($publications as $pub) : ?>
                <!-- Item -->
                <div class="col-lg-4 col-md-6 pub-item">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden pub-card">
                        <div class="pub-cover-container">
                            <?php if ($pub->thumbnail) : ?>
                                <img src="<?= ASSETS_URL ?>img/publications/<?= $pub->thumbnail ?>" class="w-100 h-100" style="object-fit: cover; display: block;" alt="<?= $pub->title_id ?>" onerror="this.onerror=null; this.src='<?= ASSETS_URL ?>img/placeholder-book.png';">
                            <?php else : ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-book fa-4x text-muted opacity-25"></i>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-white bg-opacity-75 backdrop-blur text-center">
                                <small class="fw-bold"><?= $pub->type ?></small>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" data-lang-id="<?= $pub->title_id ?>" data-lang-en="<?= $pub->title_en ?>"><?= $pub->title_id ?></h5>
                            <div class="text-muted small mb-3" data-lang-id="<?= $pub->description_id ?>" data-lang-en="<?= $pub->description_en ?>">
                                <?= $pub->description_id ?>
                            </div>
                            <div class="mt-auto pt-3 border-top d-flex gap-2 flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-light text-dark border" data-i18n="publication.badge_pdf">PDF</span>
                                    <?php if ($pub->is_paid) : ?>
                                        <span class="fw-bold text-primary">Rp <?= number_format($pub->price, 0, ',', '.'); ?></span>
                                    <?php else : ?>
                                        <span class="badge bg-success text-white px-2" data-i18n="publication.badge_free">FREE</span>
                                    <?php endif; ?>
                                </div>
                                    <div class="d-flex gap-2 mb-2">
                                        <?php if ($pub->is_paid) : ?>
                                            <?php 
                                                $wa_link = $this->waLink("Halo GoSirk, saya tertarik untuk membeli publikasi: " . $pub->title_id);
                                            ?>
                                            <a href="<?= $wa_link ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill flex-grow-1 py-2 d-flex align-items-center justify-content-center gap-2" data-i18n="publication.btn.buy_wa">
                                                <i class="fab fa-whatsapp"></i> Buy via WhatsApp
                                            </a>
                                            <?php if (!empty($pub->preview_path)) : ?>
                                                <a href="<?= ASSETS_URL ?>docs/<?= $pub->preview_path ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-flex align-items-center justify-content-center gap-2" data-i18n="publication.btn.preview">
                                                    <i class="fas fa-eye"></i> Pratinjau
                                                </a>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php $pubTitle = htmlspecialchars($pub->title_id, ENT_QUOTES); ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 flex-grow-1 pub-get" data-pub-id="<?= (int) $pub->id ?>" data-pub-title="<?= $pubTitle ?>" data-mode="open" data-i18n="publication.btn.open_pdf">Buka PDF</button>
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 flex-grow-1 pub-get" data-pub-id="<?= (int) $pub->id ?>" data-pub-title="<?= $pubTitle ?>" data-mode="download" data-i18n="publication.btn_download">Download</button>
                                        <?php endif; ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted" data-i18n="publication.gosirk.empty">Belum ada publikasi yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($publications) && count($publications) > 12) : ?>
        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
<?php endif; ?>
    </div>
</section>

<style>
    .search-container {
        border: 1px solid rgba(0,0,0,0.05);
        background: #fff;
        transition: all 0.3s ease;
    }
    .search-container:focus-within {
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.08) !important;
        border-color: #FF8F56;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pubSearch');
    const pubItems = document.querySelectorAll('.pub-item');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        pubItems.forEach(item => {
            const h5 = item.querySelector('h5');
            const desc = item.querySelector('.text-muted.small');
            
            const titleId = h5.getAttribute('data-lang-id') || '';
            const titleEn = h5.getAttribute('data-lang-en') || '';
            const descId = desc.getAttribute('data-lang-id') || '';
            const descEn = desc.getAttribute('data-lang-en') || '';
            
            const combinedText = (titleId + ' ' + titleEn + ' ' + descId + ' ' + descEn).toLowerCase();
            
            if (combinedText.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<!-- Download form (same fields as the Executive Summary request) -->
<div class="modal fade" id="pubDownloadModal" tabindex="-1" aria-labelledby="pubDownloadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" id="pubDownloadModalLabel" data-i18n="publication.modal_title">Unduh Publikasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <p class="mb-4 text-muted" data-i18n="publication.modal_desc">Isi data berikut untuk mengunduh publikasi GoSirk.</p>

        <div class="alert alert-primary bg-primary bg-opacity-10 border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <span class="material-symbols-outlined">menu_book</span>
            <div><span data-i18n="collaboration.modal.doc_label">Dokumen</span>: <strong id="pubModalTitle">...</strong></div>
        </div>

        <form id="pubDownloadForm" novalidate>
          <div class="mb-3">
            <label for="pubName" class="form-label fw-bold small text-uppercase text-muted" data-i18n="collaboration.modal.name">Nama Lengkap</label>
            <input type="text" class="form-control bg-light border-0 py-2" id="pubName" placeholder="Masukkan nama Anda" data-i18n-placeholder="collaboration.modal.name_placeholder" <?= FormRules::attrs('pub_request', 'name') ?>>
          </div>
          <div class="mb-3">
            <label for="pubEmail" class="form-label fw-bold small text-uppercase text-muted" data-i18n="collaboration.modal.email">Alamat Email</label>
            <input type="email" class="form-control bg-light border-0 py-2" id="pubEmail" placeholder="name@company.com" <?= FormRules::attrs('pub_request', 'email') ?>>
          </div>
          <div class="mb-3">
            <label for="pubOrganization" class="form-label fw-bold small text-uppercase text-muted" data-i18n="collaboration.modal.org">Organisasi / Perusahaan</label>
            <input type="text" class="form-control bg-light border-0 py-2" id="pubOrganization" placeholder="Nama organisasi Anda" data-i18n-placeholder="collaboration.modal.org_placeholder" <?= FormRules::attrs('pub_request', 'organization') ?>>
          </div>
          <div class="mb-3">
            <label for="pubJabatan" class="form-label fw-bold small text-uppercase text-muted" data-i18n="collaboration.modal.position">Jabatan</label>
            <input type="text" class="form-control bg-light border-0 py-2" id="pubJabatan" placeholder="Posisi atau jabatan Anda" data-i18n-placeholder="collaboration.modal.position_placeholder" <?= FormRules::attrs('pub_request', 'jabatan') ?>>
          </div>
          <p class="small text-muted mb-2"><i class="fas fa-lock me-1"></i><span data-i18n="publication.modal_remember">Data Anda disimpan di perangkat ini agar form berikutnya terisi otomatis.</span></p>
          <p class="form-consent small text-muted mb-3"><span data-i18n="common.consent">Dengan mengirim formulir ini, Anda menyetujui data Anda digunakan GoSirk untuk menanggapi permintaan Anda dan memberi informasi program terkait, sesuai</span> <a href="<?= BASE_URL ?>privacy" target="_blank" rel="noopener" data-i18n="common.privacy_link">Kebijakan Privasi</a>.</p>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm" data-i18n="publication.modal_submit">Unduh Sekarang</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('pubDownloadModal');
    const form = document.getElementById('pubDownloadForm');
    const fields = { name: 'pubName', email: 'pubEmail', organization: 'pubOrganization', jabatan: 'pubJabatan' };
    const t = (key, fallback) => {
        const lang = (typeof GoSirkLang !== 'undefined') ? GoSirkLang.getCurrent() : 'id';
        return key.split('.').reduce((o, k) => (o ? o[k] : undefined), resources?.[lang]?.translation) || fallback;
    };
    let pending = null; // publication being requested: { id, title, mode }

    function openForm(pub) {
        pending = pub;
        document.getElementById('pubModalTitle').textContent = pub.title;
        GosirkLead.fill('pub', true);
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    // "Buka PDF" needs a tab opened during the click, otherwise the browser blocks it as a pop-up
    function deliver(url, mode, tab) {
        if (mode === 'open') {
            if (tab) tab.location.href = url + '?view=1'; else window.location.href = url + '?view=1';
        } else {
            const a = document.createElement('a');
            a.href = url; a.download = '';
            document.body.appendChild(a); a.click(); a.remove();
        }
    }

    function requestAccess(pub, lead) {
        const fd = new FormData();
        fd.append('pub_id', pub.id);
        Object.keys(fields).forEach((k) => fd.append(k, lead[k] || ''));
        return fetch('<?= BASE_URL ?>publication/request', { method: 'POST', body: fd }).then((r) => r.json());
    }

    // Always show the form; details saved earlier are filled in automatically
    document.querySelectorAll('.pub-get').forEach((btn) => btn.addEventListener('click', () => {
        openForm({ id: btn.dataset.pubId, title: btn.dataset.pubTitle, mode: btn.dataset.mode });
    }));

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!form.checkValidity()) { form.reportValidity(); return; }
        const lead = {};
        Object.entries(fields).forEach(([k, id]) => { lead[k] = document.getElementById(id).value.trim(); });

        const pub = pending;
        const tab = pub.mode === 'open' ? window.open('about:blank', '_blank') : null;
        const submitBtn = form.querySelector('button[type="submit"]');
        const html = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> ${t('publication.preparing', 'Menyiapkan...')}`;

        requestAccess(pub, lead)
            .then((data) => {
                if (data.status !== 'success') {
                    if (tab) tab.close();
                    return Swal.fire({ title: 'Oops!', text: data.message, icon: 'error' });
                }
                GosirkLead.save(lead);
                bootstrap.Modal.getInstance(modalEl).hide();
                deliver(data.url, pub.mode, tab);
            })
            .catch(() => { if (tab) tab.close(); Swal.fire({ title: 'Error!', text: 'Terjadi kesalahan sistem.', icon: 'error' }); })
            .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = html; });
    });
});
</script>
