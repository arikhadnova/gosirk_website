<div class="gi-page">
<style>
    .service-category-tag {
        color: #FF8F56;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: block;
    }
</style>
<!-- HERO SECTION -->
<?php
$heroGI = $data['hero'];
$heroGIImages = json_decode($heroGI->image ?? '', true);
$heroGIImages = is_array($heroGIImages) ? $heroGIImages : [$heroGI->image ?? ''];
$heroGIImages = array_values(array_filter(array_map(function ($image) {
    return $image && !filter_var($image, FILTER_VALIDATE_URL) ? ASSETS_URL . 'img/' . $image : $image;
}, $heroGIImages)));
if (empty($heroGIImages)) $heroGIImages[] = ASSETS_URL . 'img/gosirk_institute_hero.png';
$heroTransition = in_array(($data['hero_transition'] ?? 'slide'), ['slide', 'fade']) ? $data['hero_transition'] : 'slide';
?>
<section class="hero-gi d-flex align-items-center justify-content-center hero-media-shell">
    <div class="hero-media-slider hero-media-slider-<?= $heroTransition ?>" aria-hidden="true">
        <?php foreach (array_slice($heroGIImages, 0, 5) as $index => $slide): ?>
            <div class="hero-media-slide <?= $index === 0 ? 'active' : '' ?>" style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(255, 255, 255, 0.2)), url('<?= htmlspecialchars($slide, ENT_QUOTES) ?>');"></div>
        <?php endforeach; ?>
    </div>
    <div class="container text-center position-relative z-2">
        <h1 class="display-3 fw-bold text-gi-gold mb-3" data-lang-id="<?= $heroGI->title_id ?>" data-lang-en="<?= $heroGI->title_en ?>" data-i18n-html="true">
            <?= $heroGI->title_id ?>
        </h1>
        <p class="lead text-light mx-auto mb-1 fs-4" style="max-width: 900px;" data-lang-id="<?= $heroGI->subtitle_id ?>" data-lang-en="<?= $heroGI->subtitle_en ?>">
            <?= $heroGI->subtitle_id ?>
        </p>
        <p class="text-light fs-5 mb-5 opacity-75" data-lang-id="<?= $heroGI->tag_id ?>" data-lang-en="<?= $heroGI->tag_en ?>"><?= $heroGI->tag_id ?></p>
        
        <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
            <a href="#services" class="btn btn-light rounded-pill px-4 py-2 text-uppercase fw-semibold" style="color: #FF8F56 !important;" data-i18n="gi.btn_services">Our Services</a>
            <a href="#portfolio" class="btn btn-outline-light rounded-pill px-4 py-2 text-uppercase" data-i18n="gi.btn_portfolio">Our Portfolio</a>
            <a href="<?= BASE_URL ?>contact" class="btn btn-outline-light rounded-pill px-4 py-2 text-uppercase" data-i18n="gi.btn_contact">Contact Us</a>
        </div>
    </div>
</section>

<style>
    .hero-media-shell { position: relative; overflow: hidden; background: #0b1120; }
    .hero-media-slider, .hero-media-slide { position: absolute; inset: 0; }
    .hero-media-slider { z-index: 0; }
    .hero-media-slide { background-size: cover; background-position: center; }
    .hero-media-slider-fade .hero-media-slide { opacity: 0; transform: scale(1.03); transition: opacity 1s ease, transform 6s ease; }
    .hero-media-slider-fade .hero-media-slide.active { opacity: 1; transform: scale(1); }
    .hero-media-slider-slide .hero-media-slide { opacity: 1; transform: translateX(100%); transition: transform 0.85s ease; }
    .hero-media-slider-slide .hero-media-slide.active { transform: translateX(0); z-index: 2; }
    .hero-media-slider-slide .hero-media-slide.previous { transform: translateX(-100%); z-index: 1; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.hero-media-slider').forEach(function(slider) {
        const slides = slider.querySelectorAll('.hero-media-slide');
        if (slides.length <= 1) return;
        let active = 0;
        setInterval(function() {
            const previous = active;
            slides[previous].classList.remove('active', 'previous');
            active = (active + 1) % slides.length;
            slides[previous].classList.add('previous');
            slides[active].classList.add('active');
            setTimeout(function() { slides[previous].classList.remove('previous'); }, 900);
        }, 5000);
    });
});
</script>

<!-- STATS SECTION -->
<section class="gi-stats">
    <div class="container">
        <div class="stats-card-wrapper shadow-lg rounded-4 p-4 p-md-5 bg-white">
            <div class="row text-center g-4">
                <?php 
                $main_impacts = array_filter($data['impacts'], fn($i) => $i->section === 'Main');
                $collapsed_impacts = array_filter($data['impacts'], fn($i) => $i->section === 'Collapse');
                ?>
                <?php if (!empty($main_impacts)) : ?>
                    <?php $idx = 0; foreach ($main_impacts as $imp) : ?>
                        <div class="col-6 col-md-3">
                            <div class="stat-item <?= $idx > 0 ? 'border-start-md' : ''; ?>">
                                <h2 class="fw-bold mb-0">
                                    <span class="counter" 
                                          data-target="<?= $imp->value; ?>" 
                                          <?= strpos($imp->value, '.') !== false ? 'data-decimals="1"' : ''; ?>>0</span><span data-i18n="units.<?= strtolower(str_replace([' ', '/', '(', ')'], '_', $imp->unit ?? '')) ?>"><?= $imp->unit; ?></span>
                                </h2>
                                <p class="text-muted small text-uppercase fw-semibold mb-0" 
                                   data-lang-id="<?= $imp->label_id; ?>" 
                                   data-lang-en="<?= $imp->label_en; ?>">
                                    <?= $imp->label_id; ?>
                                </p>
                            </div>
                        </div>
                    <?php $idx++; endforeach; ?>
                <?php else : ?>
                    <!-- Fallback if no impact data -->
                    <div class="col-12"><p class="text-muted mb-0" data-i18n="common.no_main_impact">Data dampak utama belum tersedia.</p></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Toggle Button for More Data -->
        <div class="text-center mt-5">
            <button class="btn btn-outline-gi-orange rounded-pill px-4 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#moreImpactsGI" id="impactToggleButtonGI" data-i18n="gi.impact.btn_more">
                LIHAT DATA LENGKAP
            </button>
        </div>

        <!-- More Impacts (Collapse) -->
        <div class="collapse mt-5 mb-5" id="moreImpactsGI">
            <div class="row g-4 justify-content-center">
                <?php if (!empty($collapsed_impacts)) : ?>
                    <?php foreach ($collapsed_impacts as $imp) : ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card">
                                <div class="metric-value"><?= $imp->value ?></div>
                                <div class="metric-unit" data-i18n="units.<?= strtolower(str_replace([' ', '/', '(', ')'], '_', $imp->unit ?? '')) ?>"><?= $imp->unit ?></div>
                                <div class="metric-label" data-lang-id="<?= $imp->label_id ?>" data-lang-en="<?= $imp->label_en ?>">
                                    <?= $imp->label_id ?>
                                </div>
                                <div class="metric-note" data-lang-id="<?= $imp->note_id ?>" data-lang-en="<?= $imp->note_en ?>">
                                    <?= $imp->note_id ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12 text-center text-muted" data-i18n="common.no_more_impact">Data dampak tambahan belum tersedia.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT SECTION -->
<?php
$aboutSection = $data['about_section'] ?? null;
$aboutBadgeId = $aboutSection->badge_id ?? 'Tentang Kami';
$aboutBadgeEn = $aboutSection->badge_en ?? 'About Us';
$aboutTitleId = $aboutSection->title_id ?? 'Membangun Ekosistem Pengetahuan Sirkular';
$aboutTitleEn = $aboutSection->title_en ?? 'Building a Circular Knowledge Ecosystem';
$aboutContentId = $aboutSection->content_id ?? 'GoSirk Institute merupakan bagian dari unit strategis dalam ekosistem bisnis PT GO Circular Solutions Indonesia (GoSirk) dalam membangun sistem manajemen pengetahuan yang terstruktur di bidang pengelolaan sampah. Sebagai unit khusus dalam lini usaha Capacity Building, GoSirk Institute menjadi wadah untuk mengembangkan dan menyebarluaskan pembelajaran, praktik baik, serta inovasi-inovasi yang lahir dari pengalaman nyata di lapangan.';
$aboutContentEn = $aboutSection->content_en ?? 'GoSirk Institute is part of a strategic unit within PT GO Circular Solutions Indonesia (GoSirk) business ecosystem to build a structured knowledge management system in waste management. As a dedicated Capacity Building unit, GoSirk Institute serves as a platform to develop and share learning, good practices, and innovations born from real field experience.';
$aboutImage = $aboutSection->image ?? 'gi-1.jpeg';
$aboutImageUrl = $aboutImage && filter_var($aboutImage, FILTER_VALIDATE_URL) ? $aboutImage : ASSETS_URL . 'img/' . $aboutImage;
?>
<?php if (!$aboutSection || ((int) ($aboutSection->is_active ?? 1)) === 1): ?>
<section class="py-5 section-gap">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h5 class="text-primary fw-bold text-uppercase mb-3" data-lang-id="<?= htmlspecialchars($aboutBadgeId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutBadgeEn, ENT_QUOTES) ?>"><?= $aboutBadgeId ?></h5>
                <h2 class="fw-bold mb-4 display-6" data-lang-id="<?= htmlspecialchars($aboutTitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutTitleEn, ENT_QUOTES) ?>"><?= $aboutTitleId ?></h2>
                <p class="text-muted mb-4" style="text-align: justify; line-height: 1.8;" data-lang-id="<?= htmlspecialchars($aboutContentId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContentEn, ENT_QUOTES) ?>">
                    <?= $aboutContentId ?>
                </p>
            </div>
            <div class="col-lg-6 position-relative">
                <div class="about-image-wrapper position-relative">
                    <img src="<?= htmlspecialchars($aboutImageUrl, ENT_QUOTES) ?>" alt="Tentang GoSirk Institute" class="img-fluid rounded-4 shadow-lg position-relative z-2">
                    <!-- Logo Overlay like About Page -->
                    <div class="bg-white bg-opacity-75 rounded-3 shadow-sm position-absolute m-3" style="top: 20px; left: 20px; z-index: 5; display: flex; align-items: center; justify-content: center;">
                        <img src="<?= ASSETS_URL ?>img/logo-gi.png" alt="GI Logo" class="img-fluid" style="max-height: 80px;">
                    </div>
                    <!-- Decor element -->
                    <div class="position-absolute bg-primary rounded-circle opacity-10" style="width: 200px; height: 200px; top: -20px; right: -20px; z-index: 1;"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SERVICES SECTION -->
<section id="services" class="py-5 bg-white">
    <div class="container">
        
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6" data-i18n="gi.services_title">LAYANAN KAMI</h2>
            <p class="text-muted mx-auto" style="max-width: 700px;" data-i18n="gi.services_subtitle">
                Layanan pengembangan kapasitas dan pengetahuan yang dirancang berdasarkan kebutuhan nyata di lapangan dan pengalaman praktik.
            </p>
        </div>

        <!-- Filter Bar -->
        <div class="rounded-4 mb-5 service-filter-bar">
            <div class="row align-items-center gy-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-secondary small fw-medium text-nowrap" data-i18n="gi.filter_category">Pilih Kategori</span>
                        <select id="serviceCategory" class="form-select border-0 shadow-sm rounded-pill py-2 ps-3 pe-5" style="max-width: 350px">
                            <option value="all" selected data-i18n="gi.filter_all">Semua layanan</option>
                            <option value="training" data-i18n="gi.filter_training">Training</option>
                            <option value="publikasi-riset" data-i18n="gi.filter_pub">Publikasi dan Riset</option>
                            <option value="fasilitasi-knowledge" data-i18n="gi.filter_knowledge">Fasilitasi & Knowledge Exchange</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative ms-md-auto" style="max-width: 300px;">
                        <input type="text" id="serviceSearch" class="form-control form-control-search shadow-sm py-2" placeholder="Cari layanan" data-i18n-placeholder="gi.filter_search">
                        <i class="bi bi-search search-icon-pos"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Cards Container (Scrollable) -->
        <div class="service-scroll-wrapper">
            <div id="serviceCardsContainer" class="d-flex flex-column gap-4">

                <?php if (!empty($data['services'])) : ?>
                    <?php foreach ($data['services'] as $s) : ?>
                        <!-- Card <?= $s->id ?> -->
                        <div class="card service-card border-0 rounded-4 overflow-hidden bg-white shadow-sm p-3" data-category="<?= $s->category ?>" data-title="<?= $s->title_id ?>">
                            <div class="row g-0 align-items-center">
                                <div class="col-lg-4">
                                    <div class="bg-light rounded-4 h-100 service-img-placeholder position-relative overflow-hidden">
                                        <?php if ($s->image) : ?>
                                            <img src="<?= ASSETS_URL ?>img/gi/<?= $s->image ?>" class="w-100 h-100 object-fit-cover">
                                        <?php else : ?>
                                            <img src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="GoSirk" class="opacity-10" style="width: 100px;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card-body p-4">
                                        <?php 
                                            $cat_label_id = 'Capacity Building';
                                            $cat_label_en = 'Capacity Building';
                                            if($s->category == 'training') {
                                                $cat_label_id = 'Training';
                                                $cat_label_en = 'Training';
                                                $cat_i18n_key = 'gi.filter_training';
                                            } elseif($s->category == 'publikasi-riset') {
                                                $cat_label_id = 'Publikasi dan Riset';
                                                $cat_label_en = 'Publication & Research';
                                                $cat_i18n_key = 'gi.filter_pub';
                                            } elseif($s->category == 'fasilitasi-knowledge') {
                                                $cat_label_id = 'Fasilitasi & Knowledge Exchange';
                                                $cat_label_en = 'Facilitation & Knowledge Exchange';
                                                $cat_i18n_key = 'gi.filter_knowledge';
                                            }
                                        ?>
                                        <div class="mb-1">
                                            <span class="service-category-tag mb-1" data-lang-id="<?= $cat_label_id ?>" data-lang-en="<?= $cat_label_en ?>" data-i18n="<?= $cat_i18n_key ?>"><?= $cat_label_id ?></span>
                                        </div>
                                        <h4 class="card-title fw-bold mb-3 text-truncate-2" data-lang-id="<?= strip_tags($s->title_id) ?>" data-lang-en="<?= strip_tags($s->title_en) ?>"><?= $s->title_id ?></h4>
                                        <p class="card-text text-muted mb-4 text-truncate-3" data-lang-id="<?= strip_tags($s->description_id) ?>" data-lang-en="<?= strip_tags($s->description_en) ?>">
                                            <?= strip_tags($s->description_id) ?>
                                        </p>
                                        
                                        <div class="mb-4">
                                            <a href="<?= BASE_URL ?>gi/detail/<?= $s->slug ?>" class="btn btn-gi-orange rounded-pill d-inline-flex align-items-center gap-2">
                                                <span data-i18n="gi.learn_more">Pelajari lebih lanjut</span> <i class="bi bi-box-arrow-up-right small"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="text-center py-5">
                        <p class="text-muted" data-i18n="gi.no_services">Belum ada layanan yang ditambahkan.</p>
                    </div>
                <?php endif; ?>

                <!-- No Results Message -->
                <div id="noResults" class="text-center py-5 d-none">
                    <i class="bi bi-search fs-1 text-muted opacity-25 mb-3 d-block"></i>
                    <h5 class="text-muted" data-i18n="gi.no_results_title">Layanan tidak ditemukan</h5>
                    <p class="small text-secondary" data-i18n="gi.no_results_desc">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('serviceCategory');
    const searchInput = document.getElementById('serviceSearch');
    const serviceCards = document.querySelectorAll('.service-card');
    const noResults = document.getElementById('noResults');

    function filterServices() {
        const categoryValue = categorySelect.value;
        const searchValue = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        serviceCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            const cardTitle = card.getAttribute('data-title').toLowerCase();
            
            const matchesCategory = (categoryValue === 'all' || cardCategory === categoryValue);
            const matchesSearch = cardTitle.includes(searchValue);

            if (matchesCategory && matchesSearch) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });

        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    categorySelect.addEventListener('change', filterServices);
    searchInput.addEventListener('input', filterServices);
});
</script>

<!-- PORTFOLIO & COLLABORATION SECTION -->
<section class="py-5 bg-white" id="portfolio">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6" data-i18n="gi.portfolio_title">PORTOFOLIO DAN KOLABORASI KAMI</h2>
        </div>

        <div class="d-flex flex-column gap-5">
            
            <!-- 1. Peningkatan Kapasitas Daerah -->
            <div class="portfolio-group">
                <h5 class="fw-bold mb-4" data-i18n="gi.cat1_title">1. Peningkatan Kapasitas Pengelolaan Sampah Tingkat Daerah</h5>
                <div class="row g-4">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_gi && $p->gi_category == 'daerah') : $found = true; ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 portfolio-card">
                                        <div class="portfolio-img-wrapper" style="height: 180px; overflow: hidden;">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="h-100 w-100 object-fit-cover" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="d-flex h-100 align-items-center justify-content-center bg-light opacity-50 text-secondary display-4">
                                                    <i class="<?= $p->icon_name ?: 'bi bi-building' ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h6 class="fw-bold mb-2 text-truncate-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h6>
                                            <p class="text-muted small mb-4 flex-grow-1 text-truncate-3" data-lang-id="<?= strip_tags($p->subtitle_id) ?>" data-lang-en="<?= strip_tags($p->subtitle_en) ?>"><?= $p->subtitle_id ?></p>
                                            <div class="mt-auto text-end"><a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-portfolio-outline rounded-pill px-4" data-i18n="gi.btn_more">Selengkapnya</a></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="col-12 text-center py-4">
                                <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. Pendampingan Desa -->
            <div class="portfolio-group">
                <hr class="mb-5 text-muted opacity-25">
                <h5 class="fw-bold mb-4" data-i18n="gi.cat2_title">2. Pendampingan Optimalisasi Pengelolaan Sampah Tingkat Desa</h5>
                <div class="row g-4">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_gi && $p->gi_category == 'desa') : $found = true; ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 portfolio-card">
                                        <div class="portfolio-img-wrapper" style="height: 180px; overflow: hidden;">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="h-100 w-100 object-fit-cover" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="d-flex h-100 align-items-center justify-content-center bg-light opacity-50 text-secondary display-4">
                                                    <i class="<?= $p->icon_name ?: 'bi bi-house-door-fill' ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h6 class="fw-bold mb-2 text-truncate-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h6>
                                            <p class="text-muted small mb-4 flex-grow-1 text-truncate-3" data-lang-id="<?= strip_tags($p->subtitle_id) ?>" data-lang-en="<?= strip_tags($p->subtitle_en) ?>"><?= $p->subtitle_id ?></p>
                                            <div class="mt-auto text-end"><a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-portfolio-outline rounded-pill px-4" data-i18n="gi.btn_more">Selengkapnya</a></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="col-12 text-center py-4">
                                <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 3. Kampanye Edukasi -->
            <div class="portfolio-group">
                <hr class="mb-5 text-muted opacity-25">
                <h5 class="fw-bold mb-4" data-i18n="gi.cat3_title">3. Kampanye Edukasi dan Perubahan Perilaku Masyarakat</h5>
                <div class="row g-4">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_gi && $p->gi_category == 'kampanye') : $found = true; ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 portfolio-card shadow-sm border-0 rounded-4 overflow-hidden">
                                        <div class="portfolio-img-wrapper" style="height: 180px; overflow: hidden;">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="h-100 w-100 object-fit-cover" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="d-flex h-100 align-items-center justify-content-center bg-light opacity-50 text-secondary display-4">
                                                    <i class="<?= $p->icon_name ?: 'bi bi-megaphone-fill' ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h6 class="fw-bold mb-2 text-truncate-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h6>
                                            <p class="text-muted small mb-4 flex-grow-1 text-truncate-3" data-lang-id="<?= strip_tags($p->subtitle_id) ?>" data-lang-en="<?= strip_tags($p->subtitle_en) ?>"><?= $p->subtitle_id ?></p>
                                            <div class="mt-auto text-end">
                                                <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-portfolio-outline rounded-pill px-4" data-i18n="gi.btn_more">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="col-12 text-center py-4">
                                <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 4. Penyusunan Modul -->
            <div class="portfolio-group">
                <hr class="mb-5 text-muted opacity-25">
                <h5 class="fw-bold mb-4" data-i18n="gi.cat4_title">4. Penyusunan Modul Pelatihan dan Toolkit Pengelolaan Sampah</h5>
                <div class="row g-4">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_gi && $p->gi_category == 'modul') : $found = true; ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 portfolio-card shadow-sm border-0 rounded-4 overflow-hidden">
                                        <div class="portfolio-img-wrapper" style="height: 180px; overflow: hidden;">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="h-100 w-100 object-fit-cover" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="d-flex h-100 align-items-center justify-content-center bg-light opacity-50 text-secondary display-4">
                                                    <i class="<?= $p->icon_name ?: 'bi bi-file-earmark-text-fill' ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h6 class="fw-bold mb-2 text-truncate-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h6>
                                            <p class="text-muted small mb-4 flex-grow-1 text-truncate-3" data-lang-id="<?= strip_tags($p->subtitle_id) ?>" data-lang-en="<?= strip_tags($p->subtitle_en) ?>"><?= $p->subtitle_id ?></p>
                                            <div class="mt-auto text-end">
                                                <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-portfolio-outline rounded-pill px-4" data-i18n="gi.btn_more">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="col-12 text-center py-4">
                                <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 5. Webinar & E-learning -->
            <div class="portfolio-group">
                <hr class="mb-5 text-muted opacity-25">
                <h5 class="fw-bold mb-4" data-i18n="gi.cat5_title">5. Penyelenggaraan Webinar dan Pengembangan E-learning</h5>
                <div class="row g-4">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_gi && $p->gi_category == 'webinar') : $found = true; ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 portfolio-card shadow-sm border-0 rounded-4 overflow-hidden">
                                        <div class="portfolio-img-wrapper" style="height: 180px; overflow: hidden;">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="h-100 w-100 object-fit-cover" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="d-flex h-100 align-items-center justify-content-center bg-light opacity-50 text-secondary display-4">
                                                    <i class="<?= $p->icon_name ?: 'bi bi-laptop-fill' ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h6 class="fw-bold mb-2 text-truncate-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h6>
                                            <p class="text-muted small mb-4 flex-grow-1 text-truncate-3" data-lang-id="<?= strip_tags($p->subtitle_id) ?>" data-lang-en="<?= strip_tags($p->subtitle_en) ?>"><?= $p->subtitle_id ?></p>
                                            <div class="mt-auto text-end">
                                                <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-portfolio-outline rounded-pill px-4" data-i18n="gi.btn_more">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="col-12 text-center py-4">
                                <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 6. Academic Partnership -->
            <div class="portfolio-group">
                <hr class="mb-5 text-muted opacity-25">
                <h5 class="fw-bold mb-4" data-i18n="gi.cat6_title">6. Academic Partnership & Research Support</h5>
                <div class="row g-4">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_gi && $p->gi_category == 'akademik') : $found = true; ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 portfolio-card shadow-sm border-0 rounded-4 overflow-hidden">
                                        <div class="portfolio-img-wrapper" style="height: 180px; overflow: hidden;">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="h-100 w-100 object-fit-cover" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="d-flex h-100 align-items-center justify-content-center bg-light opacity-50 text-secondary display-4">
                                                    <i class="<?= $p->icon_name ?: 'bi bi-mortarboard-fill' ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h6 class="fw-bold mb-2 text-truncate-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h6>
                                            <p class="text-muted small mb-4 flex-grow-1 text-truncate-3" data-lang-id="<?= strip_tags($p->subtitle_id) ?>" data-lang-en="<?= strip_tags($p->subtitle_en) ?>"><?= $p->subtitle_id ?></p>
                                            <div class="mt-auto text-end">
                                                <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-portfolio-outline rounded-pill px-4" data-i18n="gi.btn_more">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="col-12 text-center py-4">
                                <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted italic" data-i18n="gi.no_portfolio">Portofolio belum tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- VIDEO SECTION -->
<?php
$videoSection = $data['video_section'] ?? null;
$videoTitleId = ($videoSection->title_id ?? '') ?: 'BELAJAR BERSAMA GOSIRK';
$videoTitleEn = ($videoSection->title_en ?? '') ?: 'LEARN WITH GOSIRK';
$videoSubtitleId = ($videoSection->content_id ?? '') ?: 'Ruang pembelajaran terbuka untuk berbagi pengalaman, praktik baik, dan pengetahuan pengelolaan sampah dari lapangan.';
$videoSubtitleEn = ($videoSection->content_en ?? '') ?: 'Open learning space to share experience, best practices, and waste management knowledge from the field.';
$videoYoutubeUrl = ($videoSection->content_2_id ?? '') ?: 'https://youtube.com/@gosirk_institute';
?>
<?php if (!$videoSection || ((int) ($videoSection->is_active ?? 1)) === 1): ?>
<section class="py-5 bg-white text-dark video-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" data-lang-id="<?= htmlspecialchars($videoTitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($videoTitleEn, ENT_QUOTES) ?>"><?= htmlspecialchars($videoTitleId) ?></h2>
            <p class="text-muted mx-auto" style="max-width: 800px;" data-lang-id="<?= htmlspecialchars($videoSubtitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($videoSubtitleEn, ENT_QUOTES) ?>">
                <?= htmlspecialchars($videoSubtitleId) ?>
            </p>
        </div>

        <!-- Video Grid -->
        <div class="row g-4 mb-5">
            <?php if (!empty($data['videos'])) : ?>
                <?php foreach ($data['videos'] as $vid) :
                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $vid->url, $match);
                    $yt_id = $match[1] ?? null;

                    if ($vid->thumbnail) {
                        $thumb = ASSETS_URL . 'img/gi/videos/' . $vid->thumbnail;
                    } elseif ($yt_id) {
                        $thumb = "https://img.youtube.com/vi/$yt_id/hqdefault.jpg";
                    } else {
                        $thumb = '';
                    }
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card video-card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="video-play-trigger"
                             role="button"
                             aria-label="<?= htmlspecialchars($vid->title_id ?: 'Putar video') ?>"
                             data-bs-toggle="modal"
                             data-bs-target="#videoModal"
                             data-video-url="<?= htmlspecialchars($vid->url) ?>"
                             data-video-title-id="<?= htmlspecialchars($vid->title_id) ?>"
                             data-video-title-en="<?= htmlspecialchars($vid->title_en) ?>">
                            <div class="ratio ratio-16x9 bg-dark">
                                <?php if ($thumb) : ?>
                                    <img src="<?= $thumb ?>" class="w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($vid->title_id ?: 'Video GoSirk Institute') ?>" loading="lazy">
                                <?php endif; ?>
                                <div class="play-overlay">
                                    <div class="play-btn-circle small">
                                        <i class="bi bi-play-fill text-white fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted" data-i18n="gi.no_videos">Video belum tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- YouTube Button -->
        <div class="text-center">
            <div class="small text-muted mb-2" data-i18n="gi.video_more_insight">More Insight On</div>
            <a href="<?= htmlspecialchars($videoYoutubeUrl, ENT_QUOTES) ?>" target="_blank" rel="noopener" class="btn btn-youtube-red d-inline-flex align-items-center gap-2">
                <i class="bi bi-youtube fs-5"></i> <span data-i18n="gi.video_youtube_btn">Youtube GoSirk Institute</span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>
<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-0 mb-2">
                <h5 class="modal-title text-white fw-bold" id="videoModalLabel">Video Player</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg bg-black">
                    <iframe id="videoPlayerIframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoPlayerIframe');
    const modalTitle = document.getElementById('videoModalLabel');

    if (videoModal) {
        videoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-video-url');
            
            // Get current language and set title
            const lang = localStorage.getItem('gosirk_language') || 'en';
            const title = lang === 'en' ? button.getAttribute('data-video-title-en') : button.getAttribute('data-video-title-id');
            
            modalTitle.textContent = title;

            let embedUrl = '';

            // Extract ID and handle Playlist
            if (url.includes('list=')) {
                const listMatch = url.match(/[?&]list=([^#&?]+)/);
                if (listMatch) {
                    embedUrl = 'https://www.youtube.com/embed/videoseries?list=' + listMatch[1] + '&autoplay=1';
                }
            } else {
                const idMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/);
                if (idMatch) {
                    embedUrl = 'https://www.youtube.com/embed/' + idMatch[1] + '?autoplay=1';
                }
            }

            if (embedUrl) {
                iframe.src = embedUrl;
            } else {
                iframe.src = url;
            }
        });

        videoModal.addEventListener('hidden.bs.modal', function() {
            iframe.src = '';
        });
    }
});
</script>

<!-- LIBRARY & ARTICLES SECTION -->
<section class="py-5 bg-white mb-5">
    <div class="container overflow-hidden">
        <!-- Main Library Title -->
        <div class="text-center mb-5 mt-4">
            <h2 class="fw-bold display-6" data-i18n="gi.library_title">GOSIRK INSTITUTE LIBRARY</h2>
        </div>

        <!-- Section Header with Navigation -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold mb-0" data-i18n="gi.article_title">ARTIKEL</h2>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= BASE_URL ?>library" class="article-link" data-i18n="gi.all_articles">Semua Artikel</a>
                <div class="d-flex gap-2">
                    <button class="btn-nav-gi prev-article"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn-nav-gi next-article"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Article Swiper Slider -->
        <div class="swiper library-slider pb-4">
            <div class="swiper-wrapper">
                <?php if (!empty($data['articles'])) : ?>
                    <?php foreach ($data['articles'] as $article) : ?>
                        <div class="swiper-slide h-auto">
                            <div class="card article-card border-0 shadow-sm">
                                <div class="article-image">
                                    <?php if ($article->image) : ?>
                                        <img src="<?= ASSETS_URL ?>img/blog/<?= $article->image ?>" class="img-fluid w-100 h-100 object-fit-cover" alt="<?= $article->title_id ?>">
                                    <?php else : ?>
                                        <div class="d-flex gap-3 text-secondary opacity-25 display-4">
                                            <i class="bi bi-circle-fill" style="font-size: 2rem;"></i>
                                            <i class="bi bi-star-fill" style="font-size: 2.5rem;"></i>
                                            <i class="bi bi-square-fill" style="font-size: 2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="article-content">
                                    <div>
                                        <h5 class="article-title text-truncate-2" data-lang-id="<?= $article->title_id ?>" data-lang-en="<?= $article->title_en ?>">
                                            <?= $article->title_id ?>
                                        </h5>
                                        <span class="article-tag"><?= $article->category ?: 'Library' ?></span>
                                        <p class="article-excerpt text-truncate-3" data-lang-id="<?= strip_tags($article->content_id) ?>" data-lang-en="<?= strip_tags($article->content_en) ?>">
                                            <?= strip_tags($article->content_id) ?>
                                        </p>
                                    </div>
                                    <a href="<?= BASE_URL ?>blog/detail/<?= $article->id ?>" class="article-link" data-i18n="gi.btn_more">Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="swiper-slide h-auto">
                        <div class="text-center py-5 w-100">
                            <p class="text-muted" data-i18n="gi.no_articles">Belum ada artikel di library.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Library Slider
        new Swiper(".library-slider", {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: ".next-article",
                prevEl: ".prev-article",
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 }
            }
        });
    });
</script>

<!-- TESTIMONIALS SECTION -->
<!-- <section class="testimonial-section py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-subheader" data-i18n="gi.testimonial_badge">Bukti Kontribusi Kami</span>
            <h3 class="fw-bold" data-i18n="gi.testimonial_title">Apa Kata Peserta & Mitra?</h3>
        </div>
        
        <div class="swiper testimonials-slider">
            <div class="swiper-wrapper pb-5">
                <?php if (!empty($testimonials)) : ?>
                    <?php foreach ($testimonials as $t) : ?>
                        <div class="swiper-slide h-auto">
                            <div class="testimonial-card">
                                <span class="material-symbols-outlined quote-icon">format_quote</span>
                                <p class="testimonial-text" data-lang-id="<?= $t->content_id ?>" data-lang-en="<?= $t->content_en ?>">
                                    <?= $t->content_id ?>
                                </p>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="profile-img" style="background-image: url('<?= $t->image ? ASSETS_URL . 'img/testimonials/' . $t->image : 'https://ui-avatars.com/api/?name=' . urlencode($t->name) . '&background=FF8F56&color=fff' ?>'); background-size: cover; background-position: center;"></div>
                                    <div>
                                        <h6 class="fw-bold mb-0"><?= $t->name ?></h6>
                                        <small class="text-muted" data-lang-id="<?= $t->role_id ?>" data-lang-en="<?= $t->role_en ?>"><?= $t->role_id ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="swiper-slide h-auto w-100">
                        <div class="text-center p-5">
                            <p class="text-muted" data-i18n="home.testimonials_empty">Testimoni belum tersedia.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section> -->

<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Testimonials Slider
        new Swiper(".testimonials-slider", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
            autoplay: {
                delay: 5000,
            }
        });
    });
</script> -->

<!-- FAQ + CTA -->
<section class="py-5">
    <div class="container">
        <!-- <div class="text-center mb-5">
            <h5 class="text-primary fw-bold text-uppercase" data-i18n="gi.faq_badge">FAQ</h5>
            <h2 class="fw-bold" data-i18n="gi.faq_title">Pertanyaan Umum</h2>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="accordion accordion-flush" id="accordionGI">
                    <?php if (!empty($faqs)) : ?>
                        <?php foreach ($faqs as $index => $faq) : ?>
                            <div class="accordion-item border-0 mb-3 bg-light rounded-4 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?> bg-light rounded-4 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $faq->id ?>">
                                        <span data-lang-id="<?= $faq->question_id ?>" data-lang-en="<?= $faq->question_en ?>">
                                            <?= $faq->question_id ?>
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse<?= $faq->id ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" data-bs-parent="#accordionGI">
                                    <div class="accordion-body text-muted">
                                        <div data-lang-id="<?= $faq->answer_id ?>" data-lang-en="<?= $faq->answer_en ?>">
                                            <?= nl2br($faq->answer_id) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="text-center p-4">
                            <p class="text-muted" data-i18n="gi.faq_empty">FAQ belum tersedia untuk kategori ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div> -->

        <!-- CTA Box -->
        <div class="cta-banner rounded-5 overflow-hidden position-relative shadow-lg mt-5">
            <!-- New Gradient Overlay matching GI theme -->
            <div class="cta-overlay" style="background: linear-gradient(90deg, #FF8F56 0%, #e5763d 100%); position: absolute; inset: 0; z-index: 1;"></div>
            
            <div class="row align-items-center position-relative z-3 p-5">
                <div class="col-lg-8 text-center text-lg-start">
                    <h2 class="display-6 fw-bold text-white mb-3" data-i18n="gi.cta_box_title">Belajar dan Bertumbuh Bersama</h2>
                    <p class="text-white-50 mb-0 fs-5" data-i18n="gi.cta_box_subtitle">Terbuka untuk kolaborasi dan pengembangan kapasitas</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                    <a href="<?= BASE_URL ?>contact" class="btn btn-light rounded-pill px-5 py-3 fw-bold shadow-sm" style="color: #FF8F56;">
                        <span data-i18n="gi.cta_box_btn">Daftar Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('impactToggleButtonGI');
    const collapseElement = document.getElementById('moreImpactsGI');

    if (toggleButton && collapseElement) {
        collapseElement.addEventListener('show.bs.collapse', function () {
            const lang = localStorage.getItem('gosirk_language') || 'en';
            toggleButton.textContent = resources[lang].translation.gi.impact.btn_less;
            toggleButton.setAttribute('data-i18n', 'gi.impact.btn_less');
        });
        
        collapseElement.addEventListener('hide.bs.collapse', function () {
            const lang = localStorage.getItem('gosirk_language') || 'en';
            toggleButton.textContent = resources[lang].translation.gi.impact.btn_more;
            toggleButton.setAttribute('data-i18n', 'gi.impact.btn_more');
        });
    }
});
</script>
