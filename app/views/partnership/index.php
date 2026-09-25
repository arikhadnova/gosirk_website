
<!-- Hero Section -->
<section class="partnership-hero">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-3 text-center">
        <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInDown text-uppercase" style="color: var(--text-dark-blue);" data-lang-id="KOLABORASI UNTUK SOLUSI <br><span class='text-primary'>PERSAMPAHAN BERKELANJUTAN</span>" data-lang-en="COLLABORATION FOR <br><span class='text-primary'>SUSTAINABLE WASTE SOLUTIONS</span>" data-i18n-html="true" data-i18n="partnership.cta_title">
            KOLABORASI UNTUK SOLUSI <br>
            <span class="text-primary">PERSAMPAHAN BERKELANJUTAN</span>
        </h1>
        <p class="lead mb-5 text-secondary animate__animated animate__fadeInUp animate__delay-1s mx-auto" style="max-width: 800px;" data-i18n="partnership.hero_desc">
            Kami telah dipercaya untuk menjalankan berbagai proyek di bidang sanitasi...
        </p>
        <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeInUp animate__delay-2s mb-5">
             <a href="#partnership" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm" data-i18n="partnership.our_partnership">
                KERJASAMA KAMI
            </a>
            <a href="<?= BASE_URL ?>contact" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold" data-i18n="partnership.btn_contact">
                HUBUNGI KAMI
            </a>
        </div>
    </div>
</section>

<!-- Ongoing Project -->
<section class="py-5">
    <div class="container py-lg-5">
        <div class="section-header text-center mb-5">
            <h6 class="text-primary text-uppercase fw-bold ls-2" data-i18n="partnership.showcase">Project Showcase</h6>
            <h2 class="fw-bold fs-1" data-i18n="partnership.ongoing">Proyek Sedang Berjalan</h2>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden project-main-card">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="project-img-container h-100">
                        <img src="<?= ASSETS_URL ?>img/petugas-baju-biru.png" class="img-fluid h-100 w-100 object-fit-cover" alt="Ongoing Project">
                    </div>
                </div>
                <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold" data-i18n="partnership.clocc_badge_1">Community Impact</span>
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold ms-2" style="background-color: #FF8F56 !important; color: white !important;" data-i18n="partnership.clocc_badge_2">Village Circular</span>
                    </div>
                    <h3 class="fw-bold mb-3" 
                        data-lang-id="Program Pendampingan Desa: CLOCC x GOSIRK" 
                        data-lang-en="Village Mentoring Program: CLOCC x GOSIRK">
                        Program Pendampingan Desa: CLOCC x GOSIRK
                    </h3>
                    <div class="d-flex align-items-center text-muted mb-4">
                        <i class="fas fa-map-marker-alt me-2 text-primary" style="color: #FF8F56 !important;"></i>
                        <span data-lang-id="Kabupaten Tabanan, Bali" 
                              data-lang-en="Tabanan Regency, Bali">
                            Kabupaten Tabanan, Bali
                        </span>
                    </div>
                    <p class="text-secondary mb-4 fs-6" 
                       data-lang-id="Inisiatif kolaboratif untuk mempercepat kemandirian pengelolaan sampah di tingkat desa melalui penerapan skema bisnis sirkular yang terintegrasi, pemberdayaan UMKM lokal, dan edukasi masif kepada masyarakat." 
                       data-lang-en="A collaborative initiative to accelerate waste management independence at the village level through the implementation of an integrated circular business scheme, empowerment of local MSMEs, and massive community education.">
                        Inisiatif kolaboratif untuk mempercepat kemandirian pengelolaan sampah di tingkat desa melalui penerapan skema bisnis sirkular yang terintegrasi, pemberdayaan UMKM lokal, dan edukasi masif kepada masyarakat.
                    </p>
                    <div class="d-flex align-items-center gap-4 mb-4 flex-wrap">
                        <img src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="GoSirk" style="max-height: 40px; width: auto;">
                        <img src="<?= ASSETS_URL ?>img/Logo CLOCC.png" alt="CLOCC" style="max-height: 40px; width: auto;">
                        <img src="<?= ASSETS_URL ?>img/logo-sirk-norge.png" alt="Sirk Norge" style="max-height: 40px; width: auto;">
                        <img src="<?= ASSETS_URL ?>img/logo-kab-tabanan.png" alt="Pemkab Tabanan" style="max-height: 40px; width: auto;">
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>implementasi_partner#program-clocc" class="btn btn-primary rounded-pill px-4" style="background-color: #FF8F56; border: none;" data-i18n="partnership.see_detail">See Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Our Partnership Categories -->
<section class="partnership-categories py-5 bg-light-subtle" id="partnership">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h6 class="text-primary text-uppercase fw-bold ls-2" data-i18n="partnership.collab_model">Collaboration Model</h6>
            <h2 class="fw-bold fs-1" style="color: #FF8F56;" data-i18n="partnership.our_partnership">OUR PARTNERSHIP</h2>
        </div>

        <!-- Community Partnership -->
        <div class="category-block mb-100">
            <div class="category-header card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 position-relative overflow-hidden">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="rounded-4 overflow-hidden shadow-sm mb-4 mb-lg-0">
                            <?php if (isset($settings['ps_comm_img'])) : ?>
                                <img src="<?= ASSETS_URL ?>img/<?= $settings['ps_comm_img'] ?>" class="img-fluid" alt="Community Partnership">
                            <?php else : ?>
                                <img src="<?= ASSETS_URL ?>img/IMG_8084.jpg" class="img-fluid" alt="Community Partnership">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-7 ps-lg-5">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="fw-bold text-dark-blue mb-1" 
                                    data-lang-id="<?= $settings['ps_comm_title_id'] ?? 'Community Partnership' ?>" 
                                    data-lang-en="<?= $settings['ps_comm_title_en'] ?? 'Community Partnership' ?>">
                                    <?= $settings['ps_comm_title_id'] ?? 'Community Partnership' ?>
                                </h3>
                                <p class="text-primary fw-medium small mb-4" style="color: #FF8F56 !important;"
                                   data-lang-id="<?= $settings['ps_comm_sub_id'] ?? 'Focal on community empowerment and social impact.' ?>" 
                                   data-lang-en="<?= $settings['ps_comm_sub_en'] ?? 'Focal on community empowerment and social impact.' ?>">
                                    <?= $settings['ps_comm_sub_id'] ?? 'Focal on community empowerment and social impact.' ?>
                                </p>
                            </div>
                            <div class="category-nav d-none d-md-flex">
                                <div class="btn-nav-swiper prev-comm"><i class="fas fa-chevron-left"></i></div>
                                <div class="btn-nav-swiper next-comm"><i class="fas fa-chevron-right"></i></div>
                            </div>
                        </div>
                        <p class="text-secondary mb-0 line-height-relaxed" 
                           data-lang-id="<?= $settings['ps_comm_desc_id'] ?? '' ?>" 
                           data-lang-en="<?= $settings['ps_comm_desc_en'] ?? '' ?>">
                            <?= $settings['ps_comm_desc_id'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="swiper swiper-comm swiper-programs">
                <div class="swiper-wrapper">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_partnership && $p->partnership_category == 'community') : $found = true; ?>
                                <div class="swiper-slide h-auto">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 program-card">
                                        <div class="program-img-wrapper">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="card-img-top" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="w-100 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"><i class="<?= $p->icon_name ?: 'fas fa-folder' ?> text-muted opacity-25" style="font-size: 64px;"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold mb-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h5>
                                            <p class="text-muted small mb-0 fs-13" data-lang-id="<?= $p->subtitle_id ?>" data-lang-en="<?= $p->subtitle_en ?>"><?= $p->subtitle_id ?></p>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                                            <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="text-primary fw-bold text-decoration-none small" data-i18n="partnership.see_detail">Selengkapnya <i class="fas fa-chevron-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="swiper-slide"><p class="text-muted">Belum ada portofolio untuk kategori ini.</p></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="swiper-slide"><p class="text-muted">Portofolio tidak tersedia.</p></div>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination position-relative mt-4"></div>
            </div>
        </div>

        <!-- Academic Partnership -->
        <div class="category-block mb-100">
            <div class="category-header card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 position-relative overflow-hidden">
                <div class="row align-items-center">
                    <div class="col-lg-7 order-2 order-lg-1 pe-lg-5">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="fw-bold text-dark-blue mb-1" 
                                    data-lang-id="<?= $settings['ps_acad_title_id'] ?? 'Academic Partnership' ?>" 
                                    data-lang-en="<?= $settings['ps_acad_title_en'] ?? 'Academic Partnership' ?>">
                                    <?= $settings['ps_acad_title_id'] ?? 'Academic Partnership' ?>
                                </h3>
                                <p class="text-primary fw-medium small mb-4" style="color: #FF8F56 !important;"
                                   data-lang-id="<?= $settings['ps_acad_sub_id'] ?? 'Collaboration with research and higher education institutions.' ?>" 
                                   data-lang-en="<?= $settings['ps_acad_sub_en'] ?? 'Collaboration with research and higher education institutions.' ?>">
                                    <?= $settings['ps_acad_sub_id'] ?? 'Collaboration with research and higher education institutions.' ?>
                                </p>
                            </div>
                            <div class="category-nav d-none d-md-flex">
                                <div class="btn-nav-swiper prev-acad"><i class="fas fa-chevron-left"></i></div>
                                <div class="btn-nav-swiper next-acad"><i class="fas fa-chevron-right"></i></div>
                            </div>
                        </div>
                        <p class="text-secondary mb-0 line-height-relaxed" 
                           data-lang-id="<?= $settings['ps_acad_desc_id'] ?? '' ?>" 
                           data-lang-en="<?= $settings['ps_acad_desc_en'] ?? '' ?>">
                            <?= $settings['ps_acad_desc_id'] ?? '' ?>
                        </p>
                    </div>
                    <div class="col-lg-5 order-1 order-lg-2">
                        <div class="rounded-4 overflow-hidden shadow-sm mb-4 mb-lg-0">
                            <?php if (isset($settings['ps_acad_img'])) : ?>
                                <img src="<?= ASSETS_URL ?>img/<?= $settings['ps_acad_img'] ?>" class="img-fluid" alt="Academic Partnership">
                            <?php else : ?>
                                <img src="<?= ASSETS_URL ?>img/pexels-fauxels-3184416.jpg" class="img-fluid" alt="Academic Partnership">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper swiper-acad swiper-programs">
                <div class="swiper-wrapper">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_partnership && $p->partnership_category == 'academic') : $found = true; ?>
                                <div class="swiper-slide h-auto">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 program-card">
                                        <div class="program-img-wrapper">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="card-img-top" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="w-100 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"><i class="<?= $p->icon_name ?: 'fas fa-folder' ?> text-muted opacity-25" style="font-size: 64px;"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold mb-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h5>
                                            <p class="text-muted small mb-0 fs-13" data-lang-id="<?= $p->subtitle_id ?>" data-lang-en="<?= $p->subtitle_en ?>"><?= $p->subtitle_id ?></p>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                                            <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="text-primary fw-bold text-decoration-none small" data-i18n="partnership.see_detail">Selengkapnya <i class="fas fa-chevron-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="swiper-slide"><p class="text-muted">Belum ada portofolio untuk kategori ini.</p></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="swiper-slide"><p class="text-muted">Portofolio tidak tersedia.</p></div>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination position-relative mt-4"></div>
            </div>
        </div>

        <!-- Program Partnership -->
        <div class="category-block">
            <div class="category-header card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 position-relative overflow-hidden">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="rounded-4 overflow-hidden shadow-sm mb-4 mb-lg-0">
                            <?php if (isset($settings['ps_prog_img'])) : ?>
                                <img src="<?= ASSETS_URL ?>img/<?= $settings['ps_prog_img'] ?>" class="img-fluid" alt="Program Partnership">
                            <?php else : ?>
                                <img src="<?= ASSETS_URL ?>img/Gocircular-Indonesia-Compro-1536x864.png" class="img-fluid" alt="Program Partnership">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-7 ps-lg-5">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="fw-bold text-dark-blue mb-1" 
                                    data-lang-id="<?= $settings['ps_prog_title_id'] ?? 'Program Partnership' ?>" 
                                    data-lang-en="<?= $settings['ps_prog_title_en'] ?? 'Program Partnership' ?>">
                                    <?= $settings['ps_prog_title_id'] ?? 'Program Partnership' ?>
                                </h3>
                                <p class="text-primary fw-medium small mb-4" style="color: #FF8F56 !important;"
                                   data-lang-id="<?= $settings['ps_prog_sub_id'] ?? 'Strategic partnerships with government and CSR.' ?>" 
                                   data-lang-en="<?= $settings['ps_prog_sub_en'] ?? 'Strategic partnerships with government and CSR.' ?>">
                                    <?= $settings['ps_prog_sub_id'] ?? 'Strategic partnerships with government and CSR.' ?>
                                </p>
                            </div>
                            <div class="category-nav d-none d-md-flex">
                                <div class="btn-nav-swiper prev-prog"><i class="fas fa-chevron-left"></i></div>
                                <div class="btn-nav-swiper next-prog"><i class="fas fa-chevron-right"></i></div>
                            </div>
                        </div>
                        <p class="text-secondary mb-0 line-height-relaxed" 
                           data-lang-id="<?= $settings['ps_prog_desc_id'] ?? '' ?>" 
                           data-lang-en="<?= $settings['ps_prog_desc_en'] ?? '' ?>">
                            <?= $settings['ps_prog_desc_id'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="swiper swiper-prog swiper-programs">
                <div class="swiper-wrapper">
                    <?php if (!empty($portfolios)) : ?>
                        <?php $found = false; foreach ($portfolios as $p) : ?>
                            <?php if ($p->show_partnership && $p->partnership_category == 'program') : $found = true; ?>
                                <div class="swiper-slide h-auto">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 program-card">
                                        <div class="program-img-wrapper">
                                            <?php if ($p->cover_image) : ?>
                                                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>" class="card-img-top" alt="<?= $p->title_id ?>">
                                            <?php else : ?>
                                                <div class="w-100 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"><i class="<?= $p->icon_name ?: 'fas fa-folder' ?> text-muted opacity-25" style="font-size: 64px;"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold mb-2" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h5>
                                            <p class="text-muted small mb-0 fs-13" data-lang-id="<?= $p->subtitle_id ?>" data-lang-en="<?= $p->subtitle_en ?>"><?= $p->subtitle_id ?></p>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                                            <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="text-primary fw-bold text-decoration-none small" data-i18n="partnership.see_detail">Selengkapnya <i class="fas fa-chevron-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$found) : ?>
                            <div class="swiper-slide"><p class="text-muted">Belum ada portofolio untuk kategori ini.</p></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="swiper-slide"><p class="text-muted">Portofolio tidak tersedia.</p></div>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination position-relative mt-4"></div>
            </div>
        </div>
    </div>
</section>

<!-- Our Partners Logos -->
<section class="py-5 bg-white">
    <div class="container py-lg-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary" data-i18n="home.partners.title">OUR PARTNER</h2>
        </div>
        <div class="swiper logo-slider">
            <div class="swiper-wrapper align-items-center">
                <?php if (!empty($partners)) : ?>
                    <?php $found = false; foreach ($partners as $partner) : ?>
                        <?php if ($partner->category == 'contribution') : $found = true; ?>
                            <div class="swiper-slide text-center">
                                    <img src="<?= ASSETS_URL ?>img/partners/<?= $partner->logo ?>" alt="<?= $partner->name ?>" class="img-fluid" 
                                         style="max-height: 80px; width: auto; transition: all 0.3s;">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (!$found) : ?>
                        <div class="swiper-slide text-center"><p class="text-muted">Currently no partners in this category.</p></div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="swiper-slide text-center"><p class="text-muted">Partner logos not available.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->

<section class="partnership-cta py-5">
    <div class="container">
        <div class="cta-banner rounded-5 overflow-hidden position-relative">
            <div class="cta-overlay" style="background: linear-gradient(90deg, rgba(255, 143, 86, 0.9) 0%, rgba(13, 74, 124, 0.8) 100%);"></div>
            <div class="row align-items-center position-relative z-3 p-5">
                <div class="col-lg-8 text-center text-lg-start">
                    <h2 class="display-6 fw-bold text-white mb-3" data-i18n="partnership.cta_title">MARI BEKERJA SAMA UNTUK <br>SOLUSI PERSAMPAHAN BERKELANJUTAN</h2>
                    <p class="text-white-50 mb-4 fs-5" data-i18n="partnership.cta_desc">Tanyakan lebih lanjut tentang lini ini sekarang!</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <a href="<?= BASE_URL ?>contact" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow" style="background-color: white; color: #FF8F56; border: none;" data-i18n="partnership.btn_contact">
                        Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 p-4 opacity-25">
                 <img src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="GoSirk" style="max-height: 80px; filter: brightness(0) invert(1);">
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiperComm = new Swiper('.swiper-comm', {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.next-comm',
                prevEl: '.prev-comm',
            },
            pagination: {
                el: '.swiper-comm .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3, spaceBetween: 30 },
            }
        });

        const swiperAcad = new Swiper('.swiper-acad', {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.next-acad',
                prevEl: '.prev-acad',
            },
            pagination: {
                el: '.swiper-acad .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3, spaceBetween: 30 },
            }
        });

        const swiperProg = new Swiper('.swiper-prog', {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.next-prog',
                prevEl: '.prev-prog',
            },
            pagination: {
                el: '.swiper-prog .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3, spaceBetween: 30 },
            }
        });
        
        // Initialize Partner Logo Slider (Auto-scroll)
        const swiperLogos = new Swiper('.logo-slider', {
            slidesPerView: 2,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 3 },
                768: { slidesPerView: 4 },
                1024: { slidesPerView: 5 },
            }
        });
    });
</script>


