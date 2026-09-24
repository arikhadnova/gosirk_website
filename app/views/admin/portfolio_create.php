<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/portfolio" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left text-muted"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block">PORTFOLIO / TAMBAH BARU</span>
            <h1 class="fw-bold mb-0">Tambah Proyek Baru [VERSI BARU]</h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/portfolio_store" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <!-- Main Content: Indonesian -->
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-file-alt me-2"></i>Konten Proyek</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Basic Info -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Judul Proyek</label>
                        <input type="text" name="title_id" class="form-control form-control-lg border-primary-soft" placeholder="Contoh: Pendampingan Desa Bengkel" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Sub-judul / Nama Klien</label>
                        <input type="text" name="subtitle_id" class="form-control" placeholder="Contoh: Pemerintah Desa Bengkel, Tabanan">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Deskripsi Ringkas (Card Link)</label>
                        <textarea name="description_id" class="form-control" rows="3" placeholder="Deskripsi pendek yang muncul di card portfolio..."></textarea>
                    </div>

                    <hr class="my-4 border-dashed">

                    <!-- NEW: Media Section moved here -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Icon FontAwesome</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-icons text-muted"></i></span>
                                <input type="text" name="icon_name" class="form-control border-start-0" placeholder="fas fa-recycle" id="iconInput">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div id="iconPreview" class="bg-light rounded p-2 text-center" style="width: 40px;"><i class="fas fa-question text-muted"></i></div>
                                <small class="text-muted extra-small">Cari di <a href="https://fontawesome.com/v5/search?m=free" target="_blank">FontAwesome 5</a></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Gambar Cover (Utama)</label>
                            <input type="file" name="cover_image" class="form-control form-control-sm">
                            <small class="text-muted extra-small d-block mt-1">Muncul di listing card dan hero detail page.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">URL Video YouTube</label>
                            <input type="url" name="video_url" class="form-control form-control-sm" placeholder="https://www.youtube.com/watch?v=...">
                            <small class="text-muted extra-small d-block mt-1">Video akan ditampilkan di atas sorotan foto.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Logo Proyek Terkait (Partner/Klien)</label>
                        <div id="project-logos-container">
                            <div class="project-logo-row mb-2">
                                <div class="input-group">
                                    <input type="file" name="project_logos[]" class="form-control form-control-sm">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-logo"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-logo"><i class="fas fa-plus me-1"></i> Tambah Logo</button>
                    </div>

                </div>
            </div>

            <!-- Card 2: Detailed Content -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-align-left me-2"></i>Detail & Dokumentasi Proyek</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark"><i class="fas fa-info-circle me-1 text-primary"></i> 1. TENTANG PROYEK (Isi Detail)</label>
                        <textarea name="detail_content_id" id="editor_detail" class="form-control" rows="10" placeholder="Tuliskan detail panjang mengenai proyek di sini..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark mb-3"><i class="fas fa-walking me-1 text-primary"></i> 2. PENDEKATAN KERJA (Approach)</label>
                        <div id="approach-container">
                            <div class="p-3 bg-light rounded-3 mb-2 approach-row">
                                <div class="mb-2">
                                    <input type="text" name="approach_titles[]" class="form-control form-control-sm fw-bold border-0 bg-transparent" placeholder="Judul Tahapan/Pendekatan">
                                </div>
                                <div class="mb-0">
                                    <textarea name="approach_descs[]" class="form-control form-control-sm border-0 bg-transparent" rows="2" placeholder="Jelaskan apa yang dilakukan..."></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm btn-link text-danger remove-approach p-0 text-decoration-none extra-small">Hapus</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-approach"><i class="fas fa-plus me-1"></i> Tambah Tahapan</button>
                        <input type="hidden" name="approach_id" id="approach_json">
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark mb-3"><i class="fas fa-images me-1 text-primary"></i> 3. SOROTAN DOKUMENTASI (Highlights)</label>
                        <div id="highlights-container">
                                <div class="p-3 bg-light rounded-3 mb-2 highlight-row">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <input type="file" name="highlight_imgs[]" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="highlight_captions[]" class="form-control form-control-sm" placeholder="Keterangan foto...">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-link text-danger remove-highlight p-0"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-highlight"><i class="fas fa-plus me-1"></i> Tambah Foto</button>
                        <input type="hidden" name="highlights" id="highlights_json" value="[]">
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Options Column -->
        <div class="col-lg-4">
            <!-- Metadata & Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Metadata & Kategori</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Kategori Utama</label>
                        <select name="main_category" class="form-select">
                            <option value="capacity_building">Capacity Building (GI)</option>
                            <option value="program_development">Program Development</option>
                            <option value="consultancy">Consultancy & Advisory</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Tipe Partner (Badge)</label>
                        <select name="partner_type" class="form-select">
                            <option value="GOVERNMENT">Government</option>
                            <option value="COMMUNITY">Community</option>
                            <option value="EDUCATION">Education/Academic</option>
                            <option value="CORPORATE">Corporate/CSR</option>
                            <option value="NGO">NGO/Foundation</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Client Name</label>
                        <input type="text" name="client_name" class="form-control" placeholder="Nama instansi/perusahaan mitra...">
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Pengaturan Tampilan & Kategori Halaman</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted extra-small mb-3">Pilih halaman dan tentukan kategori spesifiknya:</p>
                    
                    <div class="d-flex flex-column gap-3">
                        <!-- Home Display -->
                        <div class="display-option-group">
                            <label class="p-3 bg-light rounded-3 d-flex align-items-center cursor-pointer border border-transparent hover-border-primary transition-all mb-2">
                                <input type="checkbox" name="show_home" id="check_home" class="form-check-input me-3" checked>
                                <div>
                                    <div class="fw-bold text-dark small">Halaman Utama (Home)</div>
                                    <div class="text-muted extra-small">Tampil di section Portfolio & Partnership.</div>
                                </div>
                            </label>
                            <div id="cat_home" class="ms-4 ps-3 border-start mb-3">
                                <label class="form-label extra-small fw-bold text-muted">Kategori di Home</label>
                                <select name="home_category" class="form-select form-select-sm">
                                    <option value="">-- Pilih Section Home --</option>
                                    <option value="institute">Capacity Building (GoSirk Institute)</option>
                                    <option value="partner">Program dan Implementasi Partner</option>
                                    <option value="advisory">Konsultansi & Advisory Strategis</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Partnership Display -->
                        <div class="display-option-group">
                            <label class="p-3 bg-light rounded-3 d-flex align-items-center cursor-pointer border border-transparent hover-border-primary transition-all mb-2">
                                <input type="checkbox" name="show_partnership" id="check_partnership" class="form-check-input me-3" checked>
                                <div>
                                    <div class="fw-bold text-dark small">Halaman Partnership</div>
                                    <div class="text-muted extra-small">Tampil di kategori Collaboration Model.</div>
                                </div>
                            </label>
                            <div id="cat_partnership" class="ms-4 ps-3 border-start mb-3">
                                <label class="form-label extra-small fw-bold text-muted">Kategori di Partnership</label>
                                <select name="partnership_category" class="form-select form-select-sm">
                                    <option value="">-- Pilih Tipe Partnership --</option>
                                    <option value="community">Community Partnership</option>
                                    <option value="academic">Academic Partnership</option>
                                    <option value="program">Program Partnership</option>
                                </select>
                            </div>
                        </div>

                        <!-- GI Display -->
                        <div class="display-option-group">
                            <label class="p-3 bg-light rounded-3 d-flex align-items-center cursor-pointer border border-transparent hover-border-primary transition-all mb-2">
                                <input type="checkbox" name="show_gi" id="check_gi" class="form-check-input me-3" checked>
                                <div>
                                    <div class="fw-bold text-dark small">GoSirk Institute (GI)</div>
                                    <div class="text-muted extra-small">Tampil di portal edukasi & knowledge.</div>
                                </div>
                            </label>
                            <div id="cat_gi" class="ms-4 ps-3 border-start">
                                <label class="form-label extra-small fw-bold text-muted">Section di GI</label>
                                <select name="gi_category" class="form-select form-select-sm">
                                    <option value="">-- Pilih Section GI --</option>
                                    <option value="daerah">1. Peningkatan Kapasitas Daerah</option>
                                    <option value="desa">2. Pendampingan Desa</option>
                                    <option value="kampanye">3. Kampanye Perubahan Perilaku</option>
                                    <option value="modul">4. Penyusunan Modul & Toolkit</option>
                                    <option value="webinar">5. Webinar & E-learning</option>
                                    <option value="akademik">6. Academic Partnership (GI)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center">
                    <p class="text-muted small mb-4">Apakah data sudah benar? Proyek akan langsung ditampilkan sesuai pengaturan.</p>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3">
                        <i class="fas fa-save me-2"></i> PUBLIKASIKAN PROYEK
                    </button>
                    <a href="<?= BASE_URL; ?>admin/portfolio" class="btn btn-light w-100 py-2 mt-2 text-muted small">Batalkan</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CKEditor Initialization
        ClassicEditor
            .create(document.querySelector('#editor_detail'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });


    // Dynamic Lists Handler
    const setupDynamicList = (containerId, addButtonId, rowClass, removeBtnClass, template) => {
        const container = document.getElementById(containerId);
        const addButton = document.getElementById(addButtonId);

        addButton.addEventListener('click', () => {
            const div = document.createElement('div');
            div.innerHTML = template.trim();
            container.appendChild(div.firstChild);
        });

        container.addEventListener('click', (e) => {
            if (e.target.classList.contains(removeBtnClass) || e.target.closest('.' + removeBtnClass)) {
                const row = e.target.closest('.' + rowClass);
                if (row) row.remove();
            }
        });
    };

    // Approach Template
    const approachTemplate = `
        <div class="p-3 bg-light rounded-3 mb-2 approach-row">
            <div class="mb-2">
                <input type="text" name="approach_titles[]" class="form-control form-control-sm fw-bold border-0 bg-transparent" placeholder="Judul Tahapan/Pendekatan">
            </div>
            <div class="mb-0">
                <textarea name="approach_descs[]" class="form-control form-control-sm border-0 bg-transparent" rows="2" placeholder="Jelaskan apa yang dilakukan..."></textarea>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-sm btn-link text-danger remove-approach p-0 text-decoration-none extra-small">Hapus</button>
            </div>
        </div>`;
    setupDynamicList('approach-container', 'add-approach', 'approach-row', 'remove-approach', approachTemplate);

    // Highlight Template
    const highlightTemplate = `
        <div class="p-3 bg-light rounded-3 mb-2 highlight-row">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="file" name="highlight_imgs[]" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <input type="text" name="highlight_captions[]" class="form-control form-control-sm" placeholder="Keterangan foto...">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-link text-danger remove-highlight p-0"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>`;
    setupDynamicList('highlights-container', 'add-highlight', 'highlight-row', 'remove-highlight', highlightTemplate);

    // Project Logo Template
    const logoTemplate = `
        <div class="project-logo-row mb-2">
            <div class="input-group">
                <input type="file" name="project_logos[]" class="form-control form-control-sm">
                <button type="button" class="btn btn-sm btn-outline-danger remove-logo"><i class="fas fa-times"></i></button>
            </div>
        </div>`;
    setupDynamicList('project-logos-container', 'add-logo', 'project-logo-row', 'remove-logo', logoTemplate);

    // Form Submission: Package JSON
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Build Approach JSON
        const approaches = [];
        document.querySelectorAll('.approach-row').forEach(row => {
            const title = row.querySelector('input[name="approach_titles[]"]').value;
            const desc = row.querySelector('textarea[name="approach_descs[]"]').value;
            if (title || desc) approaches.push({ title, desc });
        });
        document.getElementById('approach_json').value = JSON.stringify(approaches);
    });

    // Conditional Category Display
    const toggles = [
        { check: 'check_home', target: 'cat_home' },
        { check: 'check_partnership', target: 'cat_partnership' },
        { check: 'check_gi', target: 'cat_gi' }
    ];

    toggles.forEach(t => {
        const checkbox = document.getElementById(t.check);
        const section = document.getElementById(t.target);
        
        const updateVisibility = () => {
            section.style.display = checkbox.checked ? 'block' : 'none';
        };

        checkbox.addEventListener('change', updateVisibility);
        updateVisibility(); // Initial state
    });
});
</script>
