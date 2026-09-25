<section class="section py-5 bg-light" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="container text-center">
        <span class="badge bg-primary text-white rounded-pill px-3 py-2 mb-3 fw-bold" data-i18n="publication.hub_badge">KNOWLEDGE HUB</span>
        <h2 class="fw-bold display-5 mb-5" data-i18n="publication.hub_title">Pusat Publikasi</h2>
        
        <div class="row justify-content-center g-4">
            <div class="col-md-5 col-lg-4">
                <a href="<?= BASE_URL ?>publication/gosirk" class="card h-100 border-0 shadow-sm hover-up text-decoration-none text-dark p-5">
                    <div class="card-body">
                        <span class="material-symbols-outlined text-primary mb-3" style="font-size: 64px;">menu_book</span>
                        <h3 class="fw-bold mb-3" data-i18n="publication.gosirk_title">GoSirk Publications</h3>
                        <p class="text-muted" data-i18n="publication.gosirk_desc">Jelajahi laporan tahunan, policy brief, dan modul pembelajaran resmi dari GoSirk.</p>
                        <span class="btn btn-outline-primary rounded-pill mt-3" data-i18n="publication.gosirk_btn">Lihat Publikasi</span>
                    </div>
                </a>
            </div>
            <div class="col-md-5 col-lg-4">
                 <a href="<?= BASE_URL ?>publication/reference" class="card h-100 border-0 shadow-sm hover-up text-decoration-none text-dark p-5">
                    <div class="card-body">
                        <span class="material-symbols-outlined text-warning mb-3" style="font-size: 64px;">library_books</span>
                        <h3 class="fw-bold mb-3" data-i18n="publication.reference_title">Reference Publications</h3>
                        <p class="text-muted" data-i18n="publication.reference_desc">Kumpulan regulasi, studi eksternal, dan referensi terpercaya lainnya.</p>
                        <span class="btn btn-outline-warning text-dark rounded-pill mt-3" data-i18n="publication.reference_btn">Lihat Referensi</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .hover-up {
        transition: transform 0.3s ease;
    }
    .hover-up:hover {
        transform: translateY(-10px);
    }
</style>
