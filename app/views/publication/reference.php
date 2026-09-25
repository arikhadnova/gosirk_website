<!-- Custom CSS for Publication Page -->
<style>
    .pub-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .pub-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
        border-color: #014AAD;
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

<section class="section py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3 fw-bold" data-i18n="publication.header.badge">KNOWLEDGE HUB</span>
            <h2 class="fw-bold display-5 mb-3">Reference Publications</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;" data-i18n="publication.reference.desc">
                Kumpulan referensi, regulasi, dan laporan eksternal terpercaya seputar ekonomi sirkular.
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
                            <input type="text" class="form-control border-0 py-3 shadow-none bg-transparent" placeholder="Cari referensi atau regulasi..." id="pubSearch" data-i18n="publication.reference.search_placeholder">
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
                        <div class="pub-cover-container bg-white border-bottom">
                            <?php if ($pub->thumbnail) : ?>
                                <img src="<?= ASSETS_URL ?>img/publications/<?= $pub->thumbnail ?>" class="w-100 h-100" style="object-fit: cover; display: block;" alt="<?= $pub->title_id ?>" onerror="this.onerror=null; this.src='<?= ASSETS_URL ?>img/placeholder-book.png';">
                            <?php else : ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-book fa-4x text-warning opacity-25"></i>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-white bg-opacity-75 backdrop-blur text-center">
                                <small class="fw-bold text-muted"><?= $pub->type ?></small>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" data-lang-id="<?= $pub->title_id ?>" data-lang-en="<?= $pub->title_en ?>"><?= $pub->title_id ?></h5>
                            <div class="text-muted small mb-3" data-lang-id="<?= $pub->description_id ?>" data-lang-en="<?= $pub->description_en ?>">
                                <?= $pub->description_id ?>
                            </div>
                            <div class="mt-auto pt-3 border-top d-flex gap-2 flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-light text-dark border">PDF</span>
                                    <?php if ($pub->is_paid) : ?>
                                        <span class="fw-bold text-primary">Rp <?= number_format($pub->price, 0, ',', '.'); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex gap-2">
                                        <?php if ($pub->is_paid) : ?>
                                            <?php 
                                                $wa_link = $this->waLink("Halo GoSirk, saya tertarik untuk membeli referensi: " . $pub->title_id);
                                            ?>
                                            <a href="<?= $wa_link ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                                                <i class="fab fa-whatsapp"></i> Buy via WhatsApp
                                            </a>
                                        <?php else : ?>
                                            <?php if ($pub->external_link) : ?>
                                                <a href="<?= $pub->external_link ?>" target="_blank" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 flex-grow-1" data-i18n="publication.btn.external">Sumber Eksternal</a>
                                            <?php endif; ?>
                                            <?php if (!empty($pub->file_path)) : ?>
                                                <a href="<?= ASSETS_URL ?>docs/<?= $pub->file_path ?>" download class="btn btn-sm btn-warning text-dark rounded-pill px-3 flex-grow-1" data-i18n="publication.btn.download">Download</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($pub->is_paid && $pub->external_link) : ?>
                                        <a href="<?= $pub->external_link ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                                            <i class="fas fa-external-link-alt"></i> Sumber Eksternal
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted" data-i18n="publication.reference.empty">Belum ada referensi yang tersedia.</p>
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
