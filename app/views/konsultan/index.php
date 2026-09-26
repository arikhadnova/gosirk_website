<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    :root {
        --gosirk-orange: #FF8F56;
        --gosirk-orange-dark: #e5763d;
        --gi-dark: #0b1120;
        --gi-bg-light: #f8f9fa;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
        line-height: 1.8; 
        color: #334155;
        background-color: #fff;
    }

    /* Standardized Section Headers */
    .section-subheader {
        font-size: 1.25rem; /* Matches h5 in GI */
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--gosirk-orange);
        margin-bottom: 0.75rem;
        display: block;
    }

    .section-title {
        font-weight: 700;
        color: var(--gi-dark);
        margin-bottom: 1.5rem;
    }


    /* About Section - GI Style */
    .about-section {
        padding: 100px 0;
        background: white;
    }
    
    .about-image-wrapper {
        position: relative;
    }
    
    .about-image-wrapper img {
        border-radius: 20px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    }
    
    .about-logo-overlay {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 15px;
        border-radius: 12px;
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 5;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    /* Advantages Section - Non-Card Layout */
    .konsultan-advantages {
        padding: 80px 0;
        background: #fdfdfd;
    }
    
    .advantage-item {
        padding: 20px;
        transition: transform 0.3s ease;
    }
    
    .advantage-icon-box {
        width: 65px;
        height: 65px;
        background: white;
        border: 1px solid rgba(255, 143, 86, 0.2);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        color: var(--gosirk-orange);
        box-shadow: 0 10px 20px rgba(255, 143, 86, 0.05);
    }
    
    .advantage-icon-box span {
        font-size: 2.2rem;
    }
    
    .advantage-title {
        font-size: 1.5rem; /* Matches h4 in GI */
        font-weight: 700;
        color: var(--dark-blue);
        margin-bottom: 15px;
        position: relative;
        padding-bottom: 10px;
    }

    .advantage-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 30px;
        height: 3px;
        background: var(--gosirk-orange);
        border-radius: 2px;
    }
    
    .advantage-text {
        color: #64748b;
        line-height: 1.8;
    }
    
    .about-section {
        padding: 100px 0 60px;
        background: white;
    }
    
    .about-card {
        background: var(--soft-bg);
        border-radius: 24px;
        padding: 50px;
        border-left: 8px solid var(--gosirk-orange);
    }

    /* Service Cards - Redesigned to GI Style (Vertical) */
    .service-card {
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        height: 100%;
        transition: all 0.3s ease;
        background: #fff;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
    }

    .service-img-wrapper {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .service-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-card:hover .service-img-wrapper img {
        transform: scale(1.1);
    }

    .service-card .badge-scope {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        color: var(--gosirk-orange);
        font-weight: 700;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.7rem;
        text-transform: uppercase;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        z-index: 2;
    }

    .service-card .card-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .btn-learn-more {
        margin-top: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--gosirk-orange);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: gap 0.3s ease;
    }

    .btn-learn-more:hover {
        color: var(--gosirk-orange-dark);
        gap: 12px;
    }

    /* Vertical Timeline */
    .timeline-container {
        position: relative;
        padding-left: 30px;
    }

    .timeline-container::before {
        content: "";
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 2px;
        background: #f0f0f0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 40px;
        padding-left: 20px;
    }

    .timeline-item::after {
        content: "";
        position: absolute;
        left: -37px; top: 5px;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: var(--gosirk-orange);
        border: 4px solid #fff;
        box-shadow: 0 0 0 4px rgba(255, 143, 86, 0.1);
    }

    /* Check List */
    .check-list { list-style: none; padding-left: 0; }
    .check-list li {
        position: relative;
        padding-left: 30px;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    .check-list li::before {
        content: "\e5ca";
        font-family: 'Material Symbols Outlined';
        position: absolute;
        left: 0;
        color: var(--gosirk-orange);
        font-weight: bold;
    }

    /* Rounded Pill Buttons */
    .btn-gi-primary {
        background: var(--gosirk-orange);
        color: white;
        border-radius: 50px;
        padding: 12px 32px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        border: 2px solid var(--gosirk-orange);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    .btn-gi-primary:hover {
        background: white;
        color: var(--gosirk-orange);
        transform: translateY(-2px);
    }

    .btn-gi-outline {
        background: transparent;
        color: white;
        border: 2px solid white;
        border-radius: 50px;
        padding: 12px 32px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    .btn-gi-outline:hover {
        background: white;
        color: var(--gi-dark);
        transform: translateY(-2px);
    }

    .bg-soft-orange {
        background-color: rgba(255, 143, 86, 0.05);
    }

    .cta-banner {
        background: linear-gradient(90deg, var(--gosirk-orange) 0%, #ffac7d 100%);
        border-radius: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(255, 143, 86, 0.25);
    }

    .cta-banner::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .feature-icon {
        color: var(--gosirk-orange);
        font-size: 2rem;
    }

    @media (min-width: 768px) {
        .border-start-md {
            border-left: 1px solid #eee;
        }
    }

    /* Testimonials Styles */
    .testimonial-section {
        background-color: #f8f9fa;
    }
    .testimonial-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #f0f0f0;
        box-shadow: 0 8px 20px rgba(0,0,0,.06);
        transition: all 0.3s ease;
    }
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
    }
    .quote-icon {
        font-size: 48px;
        color: var(--gosirk-orange);
        opacity: 0.3;
        margin-bottom: 10px;
        font-family: 'Material Symbols Outlined';
    }
    .testimonial-text {
        font-size: 0.95rem;
        line-height: 1.7;
        color: #5e5e5e;
        font-style: italic;
        margin-bottom: 25px;
        flex-grow: 1;
    }
    .profile-img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        background: #f0f0f0;
        flex-shrink: 0;
    }
    
    /* FAQ Styles */
    .accordion-gi .accordion-item {
        border: none;
        margin-bottom: 12px;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .accordion-gi .accordion-button {
        padding: 18px 24px;
        font-weight: 600;
        color: #333;
        background: white;
        border: 1px solid #f0f0f0;
    }
    .accordion-gi .accordion-button:not(.collapsed) {
        color: var(--gosirk-orange);
        background: white !important;
        border-color: var(--gosirk-orange);
        box-shadow: none;
    }
    .accordion-gi .accordion-button:focus {
        box-shadow: none;
    }
    .accordion-gi .accordion-body {
        padding: 24px;
        color: #6c6c6c;
        background: white;
        border: 1px solid #f0f0f0;
        border-top: none;
    }
</style>

<div class="konsultan-page">
    <!-- HERO SECTION -->
    <?php
    $heroKonsultan = $data['hero'];
    $konsultanHeroImages = json_decode($heroKonsultan->image ?? '', true);
    $konsultanHeroImages = is_array($konsultanHeroImages) ? $konsultanHeroImages : [$heroKonsultan->image ?? ''];
    $konsultanHeroImages = array_values(array_filter(array_map(function ($image) {
        return $image && !filter_var($image, FILTER_VALIDATE_URL) ? ASSETS_URL . 'img/' . $image : $image;
    }, $konsultanHeroImages)));
    if (empty($konsultanHeroImages)) $konsultanHeroImages[] = 'https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=1920&q=80';
    $heroTransition = in_array(($data['hero_transition'] ?? 'slide'), ['slide', 'fade']) ? $data['hero_transition'] : 'slide';
    ?>
    <section class="hero-konsultan hero-full hero-media-shell">
        <div class="hero-media-slider hero-media-slider-<?= $heroTransition ?>" aria-hidden="true">
            <?php foreach (array_slice($konsultanHeroImages, 0, 5) as $index => $slide): ?>
                <div class="hero-media-slide <?= $index === 0 ? 'active' : '' ?>" style="background-image: url('<?= htmlspecialchars($slide, ENT_QUOTES) ?>');"></div>
            <?php endforeach; ?>
        </div>
        <div class="container position-relative">
            <h1 class="hero-full-title text-uppercase" data-lang-id="<?= $heroKonsultan->title_id ?>" data-lang-en="<?= $heroKonsultan->title_en ?>" data-i18n-html="true">
                <?= $heroKonsultan->title_id ?>
            </h1>
            <p class="hero-full-lead" data-lang-id="<?= $heroKonsultan->subtitle_id ?>" data-lang-en="<?= $heroKonsultan->subtitle_en ?>">
                <?= $heroKonsultan->subtitle_id ?>
            </p>
            <p class="hero-full-tag" data-lang-id="<?= $heroKonsultan->tag_id ?>" data-lang-en="<?= $heroKonsultan->tag_en ?>"><?= $heroKonsultan->tag_id ?></p>
            <div class="hero-full-actions">
                <a href="#tentang" class="btn btn-light" data-i18n="konsultan.explore">Eksplorasi</a>
                <a href="<?= BASE_URL ?>contact" class="btn btn-outline-light" data-i18n="gi.btn_contact">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <style>
        .hero-media-shell { position: relative; overflow: hidden; background: #0b1120; }
        .hero-media-slider, .hero-media-slide { position: absolute; inset: 0; }
        .hero-media-slider { z-index: 0; }
        .hero-media-shell > .container { z-index: 1; }
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

    <!-- ABOUT SECTION - GI Style -->
    <?php
    $aboutSection = $data['about_section'] ?? null;
    $aboutBadgeId = $aboutSection->badge_id ?? 'Tentang Layanan Kami';
    $aboutBadgeEn = $aboutSection->badge_en ?? 'About Our Service';
    $aboutTitleId = $aboutSection->title_id ?? 'Solusi Strategis Berbasis Data dan Pengalaman Lapangan';
    $aboutTitleEn = $aboutSection->title_en ?? 'Strategic Solutions Based on Data and Field Experience';
    $aboutContentId = $aboutSection->content_id ?? 'GoSirk menyediakan layanan konsultansi profesional dan berorientasi solusi untuk memperkuat sistem pengelolaan sampah di Indonesia, didukung rekam jejak solid sejak 2022 dalam menyusun kebijakan strategis dan inovasi pembiayaan.';
    $aboutContentEn = $aboutSection->content_en ?? 'GoSirk provides professional, solution-oriented consulting services to strengthen waste management systems in Indonesia, supported by a solid track record since 2022 in strategic policy development and financing innovation.';
    ?>
    <?php if (!$aboutSection || ((int) ($aboutSection->is_active ?? 1)) === 1): ?>
    <section id="tentang" class="about-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <span class="section-subheader" data-lang-id="<?= htmlspecialchars($aboutBadgeId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutBadgeEn, ENT_QUOTES) ?>"><?= $aboutBadgeId ?></span>
                    <h2 class="display-6 fw-bold mb-4" style="color: var(--dark-blue);" data-lang-id="<?= htmlspecialchars($aboutTitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutTitleEn, ENT_QUOTES) ?>"><?= $aboutTitleId ?></h2>
                    <p class="text-muted mb-0 fs-5" style="line-height: 1.8;" data-lang-id="<?= htmlspecialchars($aboutContentId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContentEn, ENT_QUOTES) ?>">
                        <?= $aboutContentId ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ADVANTAGES SECTION - Non-Card Layout -->
    <section class="konsultan-advantages">
        <div class="container">
            <div class="text-center mb-1 pb-3">
                <span class="section-subheader" data-i18n="konsultan.method_subheader">Nilai Tambah</span>
                <h2 class="section-title display-6" data-i18n="konsultan.method_title">Pendekatan Kami</h2>
                <div class="mx-auto mt-3 rounded-pill bg-orange opacity-25" style="width: 60px; height: 3px;"></div>
            </div>

            <div class="row g-4 pt-4">
                <div class="col-md-6 col-lg-3">
                    <div class="advantage-item">
                        <div class="advantage-icon-box">
                            <span class="material-symbols-outlined">map</span>
                        </div>
                        <h4 class="advantage-title" data-i18n="konsultan.adv1_title">Berbasis Pengalaman Lapangan</h4>
                        <p class="advantage-text" data-i18n="konsultan.adv1_desc">Pemahaman mendalam konteks lokal Indonesia, memastikan solusi yang aplikatif.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="advantage-item">
                        <div class="advantage-icon-box">
                            <span class="material-symbols-outlined">hub</span>
                        </div>
                        <h4 class="advantage-title" data-i18n="konsultan.adv2_title">Integratif & Solutif</h4>
                        <p class="advantage-text" data-i18n="konsultan.adv2_desc">Menggabungkan aspek teknis, fiskal, dan sosial untuk ketangguhan finansial.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="advantage-item">
                        <div class="advantage-icon-box">
                            <span class="material-symbols-outlined">handshake</span>
                        </div>
                        <h4 class="advantage-title" data-i18n="konsultan.adv3_title">Jembatan Multi-Stakeholder</h4>
                        <p class="advantage-text" data-i18n="konsultan.adv3_desc">Kolaborasi produktif Pemerintah, Swasta, Lembaga Donor, dan Masyarakat.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="advantage-item">
                        <div class="advantage-icon-box">
                            <span class="material-symbols-outlined">verified</span>
                        </div>
                        <h4 class="advantage-title" data-i18n="konsultan.adv4_title">Transparansi & Akuntabilitas</h4>
                        <p class="advantage-text" data-i18n="konsultan.adv4_desc">Menjamin proses dan hasil yang terukur dan akuntabel kepada setiap mitra.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section id="layanan" class="py-3 bg-white mt-2">
        <div class="container py-3">
            <div class="text-center mb-5">
                <span class="section-subheader" data-i18n="konsultan.service_subheader">Layanan Utama</span>
                <h2 class="section-title display-6" data-i18n="konsultan.service_title">Solusi Strategis Pilihan</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="service-card shadow-sm border-0">
                        <div class="service-img-wrapper">
                            <span class="badge-scope" data-i18n="konsultan.scope_policy">Policy</span>
                            <img loading="lazy" decoding="async" src="<?= PageImages::attr('konsultan.service_1') ?>" alt="Policy Advisory">
                        </div>
                        <div class="card-body">
                            <h4 class="fw-bold mb-3 h5" data-i18n="konsultan.service1_title">Advisori Kebijakan</h4>
                            <p class="text-muted small mb-0" data-i18n="konsultan.service1_desc">Penyusunan kajian kebijakan dan roadmap persampahan daerah (contoh: RIPS Banyuwangi, Tabanan, dan Tegal).</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="service-card shadow-sm border-0">
                        <div class="service-img-wrapper">
                            <span class="badge-scope" data-i18n="konsultan.scope_finance">Finance</span>
                            <img loading="lazy" decoding="async" src="<?= PageImages::attr('konsultan.service_2') ?>" alt="Financial Innovation">
                        </div>
                        <div class="card-body">
                            <h4 class="fw-bold mb-3 h5" data-i18n="konsultan.service2_title">Inovasi Pembiayaan</h4>
                            <p class="text-muted small mb-0" data-i18n="konsultan.service2_desc">Perancangan mekanisme pembiayaan inovatif: skema retribusi, iuran berbasis layanan, dan blended finance.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="service-card shadow-sm border-0">
                        <div class="service-img-wrapper">
                            <span class="badge-scope" data-i18n="konsultan.scope_ops">Operations</span>
                            <img loading="lazy" decoding="async" src="<?= PageImages::attr('konsultan.service_3') ?>" alt="Operational Systems">
                        </div>
                        <div class="card-body">
                            <h4 class="fw-bold mb-3 h5" data-i18n="konsultan.service3_title">Sistem Operasional</h4>
                            <p class="text-muted small mb-0" data-i18n="konsultan.service3_desc">Peningkatan efektivitas operasional dan integrasi rantai nilai daur ulang untuk efisiensi tinggi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="service-card shadow-sm border-0">
                        <div class="service-img-wrapper">
                            <span class="badge-scope" data-i18n="konsultan.scope_partner">Partnership</span>
                            <img loading="lazy" decoding="async" src="<?= PageImages::attr('konsultan.service_4') ?>" alt="PPP Facilitation">
                        </div>
                        <div class="card-body">
                            <h4 class="fw-bold mb-3 h5" data-i18n="konsultan.service4_title">Fasilitasi KPS (PPP)</h4>
                            <p class="text-muted small mb-0" data-i18n="konsultan.service4_desc">Desain model Kemitraan Pemerintah-Swasta dan kerja sama hibrida untuk mobilisasi modal dan teknologi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PORTFOLIO SECTION -->
    <!-- <section id="portfolio" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <span class="section-subheader">Our Portofolio</span>
                <h2 class="section-title display-6">Indonesian Solid Waste Association (InSWA)</h2>
                <h3 class="h4 fw-bold text-muted mt-2">Penyusunan Rencana Induk Pengelolaan Sampah (RIPS)</h3>
            </div>

            <?php if (!empty($data['portfolio_articles'])) : ?>
                <div class="row g-4 justify-content-center">
                    <?php foreach ($data['portfolio_articles'] as $article) : ?>
                        <div class="col-lg-6">
                            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm h-100">
                                <div class="mb-4 text-center overflow-hidden rounded-4" style="height: 300px;">
                                    <?php 
                                        $image = $article->image ? ASSETS_URL . 'img/blog/' . $article->image : ASSETS_URL . 'img/IMG_8084.jpg';
                                    ?>
                                    <img loading="lazy" decoding="async" src="<?= $image ?>" class="img-fluid w-100 h-100 object-fit-cover shadow-sm" alt="<?= $article->title_id ?>">
                                </div>
                                <div class="article-content">
                                    <h3 class="h4 fw-bold mb-3"><?= $article->title_id ?></h3>
                                    <div class="fs-6 mb-4 text-muted" style="line-height: 1.8;">
                                        <?= substr(strip_tags($article->content_id), 0, 250) ?>...
                                    </div>
                                    <a href="<?= BASE_URL ?>blog/detail/<?= $article->id ?>" class="btn-learn-more">
                                        Pelajari Selengkapnya <span class="material-symbols-outlined fs-6">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="text-center py-5">
                    <p class="text-muted fs-5">Belum ada rincian artikel portofolio yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </section> -->

    <!-- CTA SECTION -->
    <section id="kontak" class="py-5 mb-5">
        <div class="container">
            <div class="cta-banner rounded-5 overflow-hidden position-relative">
                <div class="row align-items-center position-relative z-3 p-4 p-md-5">
                    <div class="col-lg-8 text-center text-lg-start">
                        <h2 class="display-6 fw-bold text-white mb-3" data-i18n="konsultan.cta_title">Butuh Pendampingan Strategis?</h2>
                        <p class="text-white-50 mb-4 fs-5" data-i18n="konsultan.cta_subtitle">Jadwalkan sesi konsultasi pertama untuk membedah tantangan pengelolaan sampah di organisasi Anda.</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="<?= BASE_URL ?>contact" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm" style="color: var(--gosirk-orange); border: none;" data-i18n="konsultan.cta_button">
                        <span data-i18n="konsultan.cta_button">Hubungi Tim GoSirk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
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
</script>
