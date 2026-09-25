<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/<?= ($article->type == 'blog' ? 'articles' : 'library') ?>" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <span class="admin-header-badge mb-1 d-inline-block">PORTFOLIO & MEDIA / <?= ($article->type == 'blog' ? 'BLOG / EDIT ARTIKEL' : 'LIBRARY / EDIT RESOURCE') ?></span>
            <h1 class="fw-bold mb-0">Edit <?= ($article->type == 'blog' ? 'Artikel' : 'Resource Library') ?></h1>
        </div>
    </div>
</div>

<form action="<?= BASE_URL; ?>admin/articles_update" method="POST" enctype="multipart/form-data" id="article-form">
    <input type="hidden" name="id" value="<?= $article->id; ?>">
    <input type="hidden" name="type" value="<?= $article->type; ?>">
    <div class="row g-4">
        <div class="col-12">
            <?php Flasher::flash(); ?>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Konten Artikel</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Artikel</label>
                        <input type="text" name="title_id" class="form-control" value="<?= $article->title_id; ?>" <?= FormRules::attrs('article', 'title_id', 'update') ?>>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Konten Artikel</label>
                        <textarea name="content_id" class="form-control" rows="15" <?= FormRules::attrs('article', 'content_id', 'update') ?>><?= $article->content_id; ?></textarea>
                        <div class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i> Kapasitas maksimal konten adalah 16MB (MediumText).
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Publishing Settings</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Unggah Gambar Baru (Opsional)</label>
                        <div class="p-3 border rounded-3 text-center bg-light border-dashed mb-2 position-relative">
                             <div id="image-preview-container" class="mb-2">
                                <img id="image-preview" src="<?= ASSETS_URL; ?>img/blog/<?= $article->image; ?>" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px;">
                             </div>
                             <div id="upload-placeholder" class="d-none">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="small text-muted mb-0">Klik untuk unggah atau seret gambar ke sini</p>
                             </div>
                             <input type="file" name="image" id="image-input" class="form-control form-control-sm mt-2" accept="image/*">
                        </div>
                        <div class="small text-muted mb-2">
                            <i class="fas fa-exclamation-triangle me-1"></i> Batas ukuran: <strong>Maks 2MB</strong>. Format: JPG, PNG, WEBP.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="Environment" <?= $article->category == 'Environment' ? 'selected' : ''; ?>>Environment</option>
                            <option value="Education" <?= $article->category == 'Education' ? 'selected' : ''; ?>>Education</option>
                            <option value="Innovation" <?= $article->category == 'Innovation' ? 'selected' : ''; ?>>Innovation</option>
                            <option value="Community" <?= $article->category == 'Community' ? 'selected' : ''; ?>>Community</option>
                            <option value="Consulting" <?= $article->category == 'Consulting' ? 'selected' : ''; ?>>Consulting</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control" value="<?= $article->tags ?? ''; ?>" placeholder="Contoh: Environment, Sustainability, GoSirk" <?= FormRules::attrs('article', 'tags', 'update') ?>>
                        <div class="text-muted small mt-1">Pisahkan dengan koma (,)</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" <?= $article->status == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?= $article->status == 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" id="submit-btn">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="<?= BASE_URL; ?>admin/<?= ($article->type == 'blog' ? 'articles' : 'library') ?>" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small">Kembali</a>
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
