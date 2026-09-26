<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/<?= ($data['type'] == 'blog' ? 'articles' : 'library') ?>" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left text-muted"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block">PORTOFOLIO & MEDIA / <?= ($data['type'] == 'blog' ? 'BLOG / TULIS ARTIKEL' : 'LIBRARY / TAMBAH RESOURCE') ?></span>
            <h1 class="fw-bold mb-0"><?= ($data['type'] == 'blog' ? 'Tulis Artikel Baru' : 'Tambah Resource Library') ?></h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/articles_store" method="POST" enctype="multipart/form-data" id="article-form">
    <input type="hidden" name="type" value="<?= $data['type'] ?>">
    <div class="row g-4">
        <div class="col-12">
            <?php Flasher::flash(); ?>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Konten Artikel</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Judul Artikel</label>
                        <input type="text" name="title_id" class="form-control" placeholder="Contoh: Menuju Indonesia Bebas Sampah" <?= FormRules::attrs('article', 'title_id', 'store') ?>><?= EnField::render('title', '', '', 'text', 1) ?>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Konten Artikel</label>
                        <textarea name="content_id" class="form-control" rows="15" placeholder="Tulis isi artikel di sini..." <?= FormRules::attrs('article', 'content_id', 'store') ?>></textarea><?= EnField::render('content', '', '', 'editor', 12) ?>
                        <div class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i> Kapasitas teks maksimal 16MB.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Pengaturan Publikasi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Gambar Thumbnail</label>
                        <div class="p-3 border rounded-3 text-center bg-light border-dashed mb-2 position-relative">
                             <div id="image-preview-container" class="mb-2 d-none">
                                <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px;">
                             </div>
                             <div id="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="small text-muted mb-0">Klik untuk unggah atau seret gambar ke sini</p>
                             </div>
                             <input type="file" name="image" id="image-input" class="form-control form-control-sm mt-2" accept="image/*">
                        </div>
                        <div class="text-muted extra-small">Ukuran disarankan: 1200x800px. Maks. 10 MB, otomatis dikompres.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Kategori</label>
                        <select name="category" class="form-select">
                            <?php foreach (array_keys(Article_model::CATEGORIES) as $cat) : ?>
                                <option value="<?= $cat ?>"><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Tag</label>
                        <input type="text" name="tags" class="form-control" placeholder="Contoh: Environment, Sustainability, GoSirk" <?= FormRules::attrs('article', 'tags', 'store') ?>>
                        <div class="text-muted extra-small mt-1">Pisahkan dengan koma (,)</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft">Draf</option>
                            <option value="published">Terbit</option>
                        </select>
                    </div>
                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" id="submit-btn">
                        <i class="fas fa-paper-plane me-2"></i> Simpan Artikel
                    </button>
                    <a href="<?= BASE_URL; ?>admin/<?= ($data['type'] == 'blog' ? 'articles' : 'library') ?>" class="btn btn-light w-100 py-2 mt-2 text-muted small">Batalkan</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let editorInstance;

    ClassicEditor
        .create(document.querySelector('textarea[name="content_id"]'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Image Preview Logic
    const imageInput = document.getElementById('image-input');
    const imagePreview = document.getElementById('image-preview');
    const previewContainer = document.getElementById('image-preview-container');
    const placeholder = document.getElementById('upload-placeholder');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    // Form Submission Logic
    document.getElementById('article-form').addEventListener('submit', function(e) {
        // Sync CKEditor data to textarea
        if (editorInstance) {
            const data = editorInstance.getData();
            document.querySelector('textarea[name="content_id"]').value = data;
            
            if (!data.trim() || data === '<p>&nbsp;</p>') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Konten artikel tidak boleh kosong!',
                });
                return false;
            }
        } else {
            console.error('CKEditor instance not found!');
        }

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });
</script>

<style>
    .ck-editor__editable {
        min-height: 400px;
    }
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
    }
</style>
