<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/impact/<?= $_GET['page'] ?? 'home' ?>" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block">DATA DAMPAK / TAMBAH</span>
            <h1 class="fw-bold mb-0">Tambah Data Dampak Baru</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/impact_store" method="POST">
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary">Informasi Label</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Label Dampak</label>
                        <input type="text" name="label_id" class="form-control" placeholder="Contoh: Mitra Kerja Sama" <?= FormRules::attrs('impact', 'label_id', 'store') ?>>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 text-primary">Pengaturan Metrik</h5>
                </div>
                <div class="card-body p-4">
                    <?php $selected_page = $_GET['page'] ?? 'home'; ?>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Halaman Target</label>
                            <select name="page" class="form-select" <?= FormRules::attrs('impact', 'page', 'store') ?>>
                                <option value="home" <?= $selected_page == 'home' ? 'selected' : '' ?>>Home Page</option>
                                <option value="gi" <?= $selected_page == 'gi' ? 'selected' : '' ?>>GoSirk Institute</option>
                                <option value="ggc" <?= $selected_page == 'ggc' ? 'selected' : '' ?>>GoSirk Green Community</option>
                                <option value="go_ngompos_project" <?= $selected_page == 'go_ngompos_project' ? 'selected' : '' ?>>Go Ngompos Project</option>
                                <option value="clocc" <?= $selected_page == 'clocc' ? 'selected' : '' ?>>CLOCC Impact</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Section</label>
                            <select name="section" id="sectionSelect" class="form-select" <?= FormRules::attrs('impact', 'section', 'store') ?>>
                                <!-- Populated via JS -->
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nilai</label>
                            <input type="text" name="value" class="form-control" placeholder="Contoh: 15" <?= FormRules::attrs('impact', 'value', 'store') ?>>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Satuan (Unit)</label>
                            <input type="text" name="unit" class="form-control" placeholder="Contoh: kg, %, unit" <?= FormRules::attrs('impact', 'unit', 'store') ?>>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nomor Urut</label>
                            <input type="number" name="order_num" class="form-control" value="0" <?= FormRules::attrs('impact', 'order_num', 'store') ?>>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Section (opsional)</label>
                        <input type="text" name="section_title_id" class="form-control" placeholder="Judul kelompok data (misal: Keberlanjutan)" <?= FormRules::attrs('impact', 'section_title_id', 'store') ?>>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="note_id" class="form-control" rows="3" placeholder="Keterangan tambahan jika diperlukan..." <?= FormRules::attrs('impact', 'note_id', 'store') ?>></textarea>
                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-5">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                                <i class="fas fa-plus me-2"></i> Tambah Data Dampak
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="<?= BASE_URL; ?>admin/impact/<?= $_GET['page'] ?? 'home' ?>" class="btn btn-outline-secondary w-100 rounded-pill py-3 fw-bold">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pageSelect = document.querySelector('select[name="page"]');
    const sectionSelect = document.getElementById('sectionSelect');
    
    const sectionsByPage = {
        'home': [
            { val: 'Main', label: 'Main (Stats Utama Home)' },
            { val: 'Sustainability', label: 'Sustainability Impact' },
            { val: 'Social', label: 'Project & Social Impact' }
        ],
        'gi': [
            { val: 'Main', label: 'Main (Stats Utama GI)' },
            { val: 'Sustainability', label: 'Sustainability Impact' },
            { val: 'Social', label: 'Project & Social Impact' },
            { val: 'Collapse', label: 'Collapse (Detail GI)' }
        ],
        'ggc': [
            { val: 'Main', label: 'Main (Stats Utama GGC)' },
            { val: 'Sustainability', label: 'Sustainability Impact' },
            { val: 'Social', label: 'Project & Social Impact' },
            { val: 'Collapse', label: 'Collapse (Detail GGC)' }
        ],
        'go_ngompos_project': [
            { val: 'Main', label: 'Main (Stats Utama Go Ngompos)' },
            { val: 'Sustainability', label: 'Sustainability Impact' },
            { val: 'Social', label: 'Project & Social Impact' },
            { val: 'Collapse', label: 'Collapse (Detail Go Ngompos)' }
        ],
        'clocc': [
            { val: 'WP 1', label: 'WP 1: Training & Capacity Building' },
            { val: 'WP 2', label: 'WP 2' },
            { val: 'WP 3', label: 'WP 3' },
            { val: 'WP 4', label: 'WP 4' },
            { val: 'WP 5', label: 'WP 5' },
            { val: 'WP 6', label: 'WP 6' },
            { val: 'WP 7', label: 'WP 7' },
            { val: 'WP 8', label: 'WP 8' },
            { val: 'WP 9', label: 'WP 9' }
        ]
    };

    function updateSections() {
        const page = pageSelect.value;
        const sections = sectionsByPage[page] || [];
        
        sectionSelect.innerHTML = '';
        sections.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.val;
            opt.textContent = s.label;
            sectionSelect.appendChild(opt);
        });
    }

    pageSelect.addEventListener('change', updateSections);
    updateSections(); // Initial call
});
</script>
