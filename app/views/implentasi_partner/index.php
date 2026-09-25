<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    :root {
        --gosirk-orange: #FF8F56;
        --gosirk-orange-dark: #e5763d;
        --gi-dark: #0b1120;
        --gi-bg-light: #f8f9fa;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --orange-light: rgba(255, 143, 86, 0.15);
    }

    .border-orange-soft { border-color: var(--orange-light) !important; }
    .border-top-orange-3 { border-top: 3px solid var(--gosirk-orange) !important; }
    .border-left-orange-3 { border-left: 3px solid var(--gosirk-orange) !important; }
    .text-orange { color: var(--gosirk-orange) !important; }
    .bg-orange-soft { background-color: var(--orange-light) !important; }

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

    /* Hero Section - GI Style */
    .hero-partner {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                    url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
        min-height: 80vh;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        padding: 100px 0;
    }

    .hero-partner .display-3 {
        font-weight: 700;
        color: #ff9f43; /* GI Gold-ish Orange */
        margin-bottom: 1rem;
    }

    .hero-partner .lead {
        max-width: 900px;
        margin: 0 auto 2.5rem;
        opacity: 0.9;
        font-weight: 400;
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

    /* Focus Section - Non-Card Layout */
    .partner-focus {
        padding: 80px 0;
        background: #fdfdfd;
    }
    
    .focus-item {
        padding: 20px;
        transition: transform 0.3s ease;
    }
    
    .focus-icon-box {
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
    
    .focus-icon-box span {
        font-size: 2.2rem;
    }
    
    .focus-title {
        font-size: 1.5rem; /* Matches h4 in GI */
        font-weight: 700;
        color: var(--dark-blue);
        margin-bottom: 15px;
        position: relative;
        padding-bottom: 10px;
    }

    .focus-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 30px;
        height: 3px;
        background: var(--gosirk-orange);
        border-radius: 2px;
    }
    
    .focus-text {
        color: #64748b;
        line-height: 1.8;
    }

    /* Stats Section Adjustment - No longer overlapping */
    .partner-stats {
        position: relative;
        padding-top: 80px;
        padding-bottom: 80px;
    }

    .stats-card-wrapper {
        background: white;
        border-radius: 30px;
        padding: 60px 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }

    

    .about-card {
        background: var(--soft-bg);
        border-radius: 24px;
        padding: 50px;
        border-left: 8px solid var(--gosirk-orange);
    }

    .problem-item {
        padding: 10px;
        transition: all 0.3s ease;
    }

    .problem-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 143, 86, 0.1);
        color: var(--gosirk-orange);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .problem-icon .material-symbols-outlined {
        font-size: 2rem; /* fs-1 equivalent */
    }

    /* Service Cards - GI Style */
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

    /* Workflow Timeline */
    .workflow-item {
        background: white;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #f0f0f0;
        height: 100%;
        position: relative;
        transition: all 0.3s ease;
    }

    .workflow-item:hover {
        border-color: var(--gosirk-orange);
    }

    .workflow-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: rgba(255, 143, 86, 0.15);
        position: absolute;
        top: 15px;
        right: 25px;
        line-height: 1;
    }

    /* Rounded Pill Buttons - GI Style */
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

    .bg-gi-light {
        background-color: var(--gi-bg-light);
    }

    .cta-banner {
        background: linear-gradient(90deg, var(--gosirk-orange) 0%, #ffac7d 100%);
        border-radius: 30px;
        padding: 70px 40px;
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

    .check-list-item i {
        color: var(--gosirk-orange);
        font-size: 1.1rem;
    }

    @media (min-width: 768px) {
        .border-start-md {
            border-left: 1px solid #eee;
        }
    }

    /* Testimonials Styles */
    .testimonial-section {
        background-color: #f8f9fa;
        overflow: hidden;
    }
    .testimonial-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    .testimonial-card:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        transform: translateY(-5px);
    }
    .quote-icon {
        font-size: 3rem;
        color: var(--gosirk-orange);
        opacity: 0.2;
        margin-bottom: -20px;
    }
    .testimonial-text {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #475569;
        font-style: italic;
        margin-bottom: 30px;
        flex-grow: 1;
    }
    .profile-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        background: #e2e8f0;
    }
    
    /* FAQ Styles */
    .accordion-gi .accordion-item {
        border: none;
        margin-bottom: 15px;
        border-radius: 16px !important;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .accordion-gi .accordion-button {
        padding: 20px 25px;
        font-weight: 700;
        color: var(--gi-dark);
        background: white;
    }
    .accordion-gi .accordion-button:not(.collapsed) {
        color: var(--gosirk-orange);
        background: white;
        box-shadow: none;
    }
    .accordion-gi .accordion-button:focus {
        box-shadow: none;
    }
    .accordion-gi .accordion-body {
        padding: 0 25px 25px;
        color: #64748b;
        background: white;
    }

    /* Updated CTA Styles */
    .partnership-cta .cta-banner {
        border-radius: 30px;
        overflow: hidden;
        position: relative;
        padding: 0;
        background: transparent;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
    .cta-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
    }
    .cta-content {
        position: relative;
        z-index: 3;
        padding: 80px 60px;
    }

    /* GI Portfolio Style Sections */
    .section-header-sm {
        font-weight: 800;
        font-size: 1.2rem;
        text-transform: uppercase;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }
    
    .target-container {
        background-color: #f8f9fa;
        padding: 40px;
        border-radius: 20px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .impact-grid-item {
        text-align: center;
        padding: 20px;
    }
    .impact-value-blue {
        color: var(--gosirk-orange);
        font-weight: 800;
        font-size: 1.8rem;
        display: block;
    }
    .impact-label-gray {
        color: #666;
        font-size: 0.8rem;
    }
    
    .highlight-card {
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        height: 250px;
        margin-bottom: 30px;
    }
    .highlight-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .highlight-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        display: flex;
        align-items: flex-end;
        padding: 20px;
        color: white;
    }

    /* IMPACT STYLES */
    .impact-tabs .nav-link {
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .impact-tabs .nav-link.active {
        background: transparent;
        color: var(--gosirk-orange);
        border-bottom-color: var(--gosirk-orange);
    }
    .impact-tabs .nav-link:hover:not(.active) {
        color: var(--gosirk-orange);
        opacity: 0.7;
    }
    .wp-header {
        background: var(--orange-light);
        border-left: 4px solid var(--gosirk-orange);
        padding: 1.5rem;
        border-radius: 0 12px 12px 0;
        margin-bottom: 2rem;
    }
    .impact-table {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .impact-table thead th {
        background: var(--gi-dark);
        color: white;
        border: none;
        padding: 1.2rem;
        font-weight: 600;
    }
    .impact-table tbody td {
        padding: 1.2rem;
        vertical-align: middle;
        border-color: #f1f5f9;
    }
    .impact-val-box {
        background: var(--orange-light);
        color: var(--gosirk-orange);
        font-weight: 800;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 1.1rem;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }

    .metric-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        height: 100%;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: var(--gosirk-orange);
    }
    .metric-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gosirk-orange);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .metric-unit {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
    }
    .metric-label {
        font-size: 0.9rem;
        color: var(--gi-dark);
        font-weight: 700;
        line-height: 1.4;
        margin-top: 0.5rem;
    }
    .metric-note {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px dashed #eee;
        font-style: italic;
    }
    .accordion-gi .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23f26522'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    .text-orange {
        color: var(--gosirk-orange) !important;
    }

    @media (max-width: 991px) {
        .impact-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 0.5rem;
        }
        .impact-tabs::-webkit-scrollbar {
            height: 4px;
        }
        .impact-tabs::-webkit-scrollbar-thumb {
            background: var(--orange-light);
            border-radius: 10px;
        }
    }
</style>

<div class="partner-page">
    <!-- HERO SECTION -->
    <?php
    $heroPartner = $data['hero'];
    $partnerHeroImageData = $heroPartner ? ($heroPartner->image ?? '') : 'IMG_8084.jpg';
    $partnerHeroImages = json_decode($partnerHeroImageData, true);
    $partnerHeroImages = is_array($partnerHeroImages) ? $partnerHeroImages : [$partnerHeroImageData];
    $partnerHeroImages = array_values(array_filter(array_map(function ($image) {
        return $image && !filter_var($image, FILTER_VALIDATE_URL) ? ASSETS_URL . 'img/' . $image : $image;
    }, $partnerHeroImages)));
    if (empty($partnerHeroImages)) $partnerHeroImages[] = ASSETS_URL . 'img/IMG_8084.jpg';
    $heroTransition = in_array(($data['hero_transition'] ?? 'slide'), ['slide', 'fade']) ? $data['hero_transition'] : 'slide';
    ?>
    <section class="hero-partner hero-media-shell">
        <div class="hero-media-slider hero-media-slider-<?= $heroTransition ?>" aria-hidden="true">
            <?php foreach (array_slice($partnerHeroImages, 0, 5) as $index => $slide): ?>
                <div class="hero-media-slide <?= $index === 0 ? 'active' : '' ?>" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= htmlspecialchars($slide, ENT_QUOTES) ?>');"></div>
            <?php endforeach; ?>
        </div>
        <div class="container position-relative">
            <?php if ($heroPartner && trim(strip_tags($heroPartner->title_id)) !== '') : // title from Admin > Hero > Implementasi Partner ?>
            <h1 class="display-3 fw-bold text-uppercase mb-3" data-i18n-html="true" data-lang-id="<?= htmlspecialchars($heroPartner->title_id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($heroPartner->title_en ?: $heroPartner->title_id, ENT_QUOTES) ?>">
                <?= $heroPartner->title_id ?>
            </h1>
            <?php else : ?>
            <h1 class="display-3 fw-bold text-uppercase mb-3" data-i18n="partner.main_title">IMPLEMENTASI PARTNER</h1>
            <?php endif; ?>
            <p class="lead fs-4 mb-2 text-light opacity-90 mx-auto" style="max-width: 900px;" data-lang-id="<?= $heroPartner ? $heroPartner->subtitle_id : 'Program pendampingan desa dan pengembangan komunitas berbasis ekonomi sirkular.' ?>" data-lang-en="<?= $heroPartner ? $heroPartner->subtitle_en : 'Village assistance program and community development based on circular economy.' ?>">
                <?= $heroPartner ? $heroPartner->subtitle_id : 'Program pendampingan desa dan pengembangan komunitas berbasis ekonomi sirkular.' ?>
            </p>
            <p class="text-light fs-5 mb-5 opacity-75 fw-medium" data-lang-id="<?= $heroPartner ? $heroPartner->tag_id : '#GoSirkImpact' ?>" data-lang-en="<?= $heroPartner ? $heroPartner->tag_en : '#GoSirkImpact' ?>"><?= $heroPartner ? $heroPartner->tag_id : '#GoSirkImpact' ?></p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="#tentang" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-uppercase" style="color: var(--gosirk-orange) !important;" data-i18n="partner.cta_explore">Eksplorasi</a>
                <a href="<?= BASE_URL ?>contact" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold text-uppercase" data-i18n="partner.cta_secondary">Hubungi Kami</a>
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

    <!-- MITRA PENGEMBANGAN PROYEK & IMPLEMENTASI SECTION -->
    <section id="mitra-pengembangan" class="py-5 bg-white">
        <div class="container">
            <!-- ABOUT SECTION - Centralized like Konsultansi -->
            <?php
            $aboutSection = $data['about_section'] ?? null;
            $aboutBadgeId = $aboutSection->badge_id ?? 'MITRA PENGEMBANGAN';
            $aboutBadgeEn = $aboutSection->badge_en ?? 'DEVELOPMENT PARTNER';
            $aboutTitleId = $aboutSection->title_id ?? 'Tentang Layanan Kami';
            $aboutTitleEn = $aboutSection->title_en ?? 'About Our Service';
            $aboutContentId = $aboutSection->content_id ?? 'GO Sirk berperan sebagai <b>mitra pengembangan dan implementasi proyek</b> untuk mentransformasi sampah menjadi solusi yang <b>berkelanjutan, inklusif, dan inovatif</b>. Kami mendampingi mitra sejak tahap perencanaan hingga pelaksanaan di lapangan untuk memastikan proyek berjalan <b>efektif secara teknis</b>, terukur, serta menghasilkan <b>dampak sosial dan lingkungan</b> yang nyata.';
            $aboutContentEn = $aboutSection->content_en ?? 'GO Sirk acts as a <b>project development and implementation partner</b> to transform waste into <b>sustainable, inclusive, and innovative</b> solutions. We assist partners from planning to field execution to ensure projects run <b>effectively from a technical standpoint</b>, are measurable, and create real <b>social and environmental impact</b>.';
            $aboutContent2Id = $aboutSection->content_2_id ?? '<b>Fokus utama</b> kami adalah memastikan keberhasilan implementasi melalui penguatan kolaborasi, tata kelola, dan model operasional yang relevan dengan konteks lokal.';
            $aboutContent2En = $aboutSection->content_2_en ?? 'Our <b>main focus</b> is ensuring implementation success through stronger collaboration, governance, and operating models relevant to the local context.';
            ?>
            <?php if (!$aboutSection || ((int) ($aboutSection->is_active ?? 1)) === 1): ?>
            <div class="row justify-content-center mb-5 pb-4">
                <div class="col-lg-10 text-center">
                    <span class="section-subheader" data-lang-id="<?= htmlspecialchars($aboutBadgeId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutBadgeEn, ENT_QUOTES) ?>"><?= $aboutBadgeId ?></span>
                    <h2 class="display-6 fw-bold mb-4" style="color: var(--dark-blue);" data-lang-id="<?= htmlspecialchars($aboutTitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutTitleEn, ENT_QUOTES) ?>"><?= $aboutTitleId ?></h2>
                    <div class="text-muted fs-5" style="line-height: 1.8;">
                        <p data-lang-id="<?= htmlspecialchars($aboutContentId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContentEn, ENT_QUOTES) ?>">
                            <?= $aboutContentId ?>
                        </p>
                        <p class="mb-0" data-lang-id="<?= htmlspecialchars($aboutContent2Id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContent2En, ENT_QUOTES) ?>">
                            <?= $aboutContent2Id ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

    <!-- SERVICE TYPES SECTION - Redesigned like Konsultansi cards -->
            <div class="mb-5">
                <div class="text-center mb-5">
                    <span class="section-subheader" data-i18n="partner.dev_services_subheader">Services</span>
                    <h2 class="section-title display-6" data-i18n="partner.dev_services_title">Jenis Layanan</h2>
                </div>
                
                <div class="row g-4 justify-content-center">
                    <!-- Service 1 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="service-card shadow-sm border-0">
                            <div class="service-img-wrapper">
                                <span class="badge-scope" data-i18n="partner.scope_design">Design</span>
                                <img src="<?= PageImages::attr('partner.service_1') ?>" alt="Project Design">
                            </div>
                            <div class="card-body">
                                <h4 class="fw-bold mb-3 h5" data-i18n="partner.dev_service1_title">1. Perancangan Program & Proyek</h4>
                                <p class="text-muted small mb-0 text-truncate-3" data-i18n="partner.dev_service1_desc">Penyusunan desain program berbasis kebutuhan lapangan, termasuk tujuan, indikator, rencana kerja, dan strategi implementasi.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Service 2 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="service-card shadow-sm border-0">
                            <div class="service-img-wrapper">
                                <span class="badge-scope" data-i18n="partner.scope_support">Support</span>
                                <img src="<?= PageImages::attr('partner.service_2') ?>" alt="Field Implementation Support">
                            </div>
                            <div class="card-body">
                                <h4 class="fw-bold mb-3 h5" data-i18n="partner.dev_service2_title">2. Pendampingan Implementasi Lapangan</h4>
                                <p class="text-muted small mb-0 text-truncate-3" data-i18n="partner.dev_service2_desc">Dukungan pelaksanaan proyek secara end-to-end agar operasional berjalan efektif, sesuai standar, dan mencapai target.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Service 3 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="service-card shadow-sm border-0">
                            <div class="service-img-wrapper">
                                <span class="badge-scope" data-i18n="partner.scope_engagement">Engagement</span>
                                <img src="<?= PageImages::attr('partner.service_3') ?>" alt="Community Engagement">
                            </div>
                            <div class="card-body">
                                <h4 class="fw-bold mb-3 h5" data-i18n="partner.dev_service3_title">3. Penguatan Keterlibatan Komunitas</h4>
                                <p class="text-muted small mb-0 text-truncate-3" data-i18n="partner.dev_service3_desc">Fasilitasi kolaborasi antara komunitas, pemerintah, sektor swasta, dan mitra lokal untuk membangun kepemilikan program dan keberlanjutan.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Service 4 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="service-card shadow-sm border-0">
                            <div class="service-img-wrapper">
                                <span class="badge-scope" data-i18n="partner.scope_system">System</span>
                                <img src="<?= PageImages::attr('partner.service_4') ?>" alt="Waste Management Systems">
                            </div>
                            <div class="card-body">
                                <h4 class="fw-bold mb-3 h5" data-i18n="partner.dev_service4_title">4. Pengembangan Sistem Pengelolaan Sampah</h4>
                                <p class="text-muted small mb-0 text-truncate-3" data-i18n="partner.dev_service4_desc">Penyusunan sistem operasional yang efisien secara teknis, serta berkelanjutan secara finansial dan lingkungan untuk mendorong ekonomi sirkular daerah.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Service 5 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="service-card shadow-sm border-0">
                            <div class="service-img-wrapper">
                                <span class="badge-scope" data-i18n="partner.scope_local">Local</span>
                                <img src="<?= PageImages::attr('partner.service_5') ?>" alt="Local Solutions">
                            </div>
                            <div class="card-body">
                                <h4 class="fw-bold mb-3 h5" data-i18n="partner.dev_service5_title">5. Pengembangan Solusi Tepat Guna & Bisnis Lokal</h4>
                                <p class="text-muted small mb-0 text-truncate-3" data-i18n="partner.dev_service5_desc">Perancangan solusi berbasis potensi lokal, termasuk skema unit usaha, kemitraan, dan pendekatan yang praktis serta mudah direplikasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pendekatan Kami -->
    <section class="partner-focus">
        <div class="container">
            <div class="text-center">
                <span class="section-subheader" data-i18n="partner.focus_subheader">Strategi Kami</span>
                <h2 class="section-title display-6" data-i18n="partner.focus_title">Pendekatan Kami</h2>
                <div class="mx-auto mt-3 rounded-pill bg-orange opacity-25" style="width: 60px; height: 3px;"></div>
            </div>
            
            <div class="row g-4 pt-4">
                <div class="col-md-6 col-lg-3">
                    <div class="focus-item">
                        <div class="focus-icon-box">
                            <span class="material-symbols-outlined">groups</span>
                        </div>
                        <h4 class="focus-title" data-i18n="partner.focus1_title">Holistik & Inklusif</h4>
                        <p class="focus-text" data-i18n="partner.focus1_desc">Pemberdayaan komunitas lokal, keterlibatan aktif pemangku kepentingan, dan kolaborasi strategis sektor swasta, mengintegrasikan 5 aspek persampahan; aspek teknis, sosial, kelembagaan, regulasi, dan keuangan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="focus-item">
                        <div class="focus-icon-box">
                            <span class="material-symbols-outlined">handshake</span>
                        </div>
                        <h4 class="focus-title" data-i18n="partner.focus2_title">Kolaboratif</h4>
                        <p class="focus-text" data-i18n="partner.focus2_desc">Membangun kemitraan strategis lintas sektor untuk memperkuat efektivitas implementasi dan memperluas dukungan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="focus-item">
                        <div class="focus-icon-box">
                            <span class="material-symbols-outlined">eco</span>
                        </div>
                        <h4 class="focus-title" data-i18n="partner.focus3_title">Solusi Berkelanjutan</h4>
                        <p class="focus-text" data-i18n="partner.focus3_desc">Mendukung sistem pengelolaan sampah yang efisien dalam rangka mendorong terwujudnya ekonomi sirkular daerah.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="focus-item">
                        <div class="focus-icon-box">
                            <span class="material-symbols-outlined">target</span>
                        </div>
                        <h4 class="focus-title" data-i18n="partner.focus4_title">Tepat Guna & Terukur</h4>
                        <p class="focus-text" data-i18n="partner.focus4_desc">Menghadirkan solusi yang sesuai realitas lapangan, praktis diterapkan, serta didorong oleh target, indikator, dan capaian yang jelas.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    <section id="program-clocc" class="about-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subheader" data-i18n="partner.portfolio_project_subheader">Rekam Jejak</span>
                <h2 class="section-title display-6" data-i18n="partner.portfolio_project_title">Portofolio Project Kami</h2>
                <div class="mx-auto mt-3 rounded-pill bg-orange opacity-25" style="width: 60px; height: 3px;"></div>
            </div>

            <!-- PARTNER LOGOS -->
            <div class="row align-items-center justify-content-center g-4 g-md-5 mb-5">
                <div class="col-6 col-md-2 text-center">
                    <img src="<?= PageImages::attr('clocc.logo_1') ?>" alt="GoSirk" class="img-fluid" style="max-height: 70px; width: auto;">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="<?= PageImages::attr('clocc.logo_2') ?>" alt="CLOCC" class="img-fluid" style="max-height: 70px; width: auto;">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="<?= PageImages::attr('clocc.logo_3') ?>" alt="Sirk Norge" class="img-fluid" style="max-height: 70px; width: auto;">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="<?= PageImages::attr('clocc.logo_4') ?>" alt="Pemkab Tabanan" class="img-fluid" style="max-height: 70px; width: auto;">
                </div>
            </div>

            <div class="row g-5">
                <!-- Left Column: Narrative -->
                <div class="col-lg-7">
                    <div class="ps-4 border-start border-3 border-orange-soft">
                        <span class="section-subheader" data-i18n="partner.overview_badge">Program Overview</span>
                        <h3 class="fs-3 fw-bold mb-4" style="color: var(--dark-blue); line-height: 1.3;" data-i18n="partner.hero_title">Bersama Menghadapi Tantangan Persampahan di Indonesia</h3>
                        <div class="text-muted mb-3" style="text-align: justify; line-height: 1.8;">
                            <p data-i18n="partner.desc_p1">
                                Program Clean Oceans through Clean Communities (CLOCC), yang dimiliki oleh Sirk Norge dan didanai oleh NORAD...
                            </p>
                            <p class="mb-0" data-i18n="partner.desc_p2">
                                Tujuan dari program pendampingan desa di desa-desa percontohan di Kabupaten Tabanan...
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Role & SOW -->
                <div class="col-lg-5 col-xl-5">
                    <div class="p-4 rounded-4 border-start border-3 border-orange-soft mb-4 shadow-sm">
                        <h5 class="fw-bold mb-3" style="color: var(--gi-dark);" data-i18n="partner.gosirk_role_title">Peran GoSirk</h5>
                        <p class="small text-muted mb-0 position-relative z-1" style="line-height: 1.6;" data-i18n="partner.gosirk_role_desc">
                            Sebagai Mitra Pelaksana Lokal (Local Implementing Partner) CLOCC di Indonesia...
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-4 border-start border-3 border-orange-soft shadow-sm">
                        <h5 class="fw-bold mb-3" style="color: var(--gi-dark);" data-i18n="partner.sow_title">Scope of Work</h5>
                        <p class="small text-muted mb-3" data-i18n="partner.sow_desc">
                            Program ini berupaya mencapai target 100% melalui:
                        </p>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex gap-2 mb-3">
                                <span class="material-symbols-outlined text-orange small">check_circle</span>
                                <span class="small text-muted" data-i18n="partner.sow_item1">Capacity building</span>
                            </li>
                            <li class="d-flex gap-2 mb-3">
                                <span class="material-symbols-outlined text-orange small">check_circle</span>
                                <span class="small text-muted" data-i18n="partner.sow_item2">Infrastructure</span>
                            </li>
                            <li class="d-flex gap-2 mb-3">
                                <span class="material-symbols-outlined text-orange small">check_circle</span>
                                <span class="small text-muted" data-i18n="partner.sow_item3">Institutional strengthening</span>
                            </li>
                            <li class="d-flex gap-2 mb-3">
                                <span class="material-symbols-outlined text-orange small">check_circle</span>
                                <span class="small text-muted" data-i18n="partner.sow_item4">Policy advocacy</span>
                            </li>
                            <li class="d-flex gap-2">
                                <span class="material-symbols-outlined text-orange small">check_circle</span>
                                <span class="small text-muted" data-i18n="partner.sow_item5">Education & awareness</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM_IMPLEMENTATION SECTION (Combined Background & Pilot Villages) -->
    <section id="program-implementation" class="py-5 bg-white">
        <div class="container">
            <!-- Background Part -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h2 class="display-6 fw-bold mb-3" style="color: var(--gi-dark);" data-i18n="partner.background_title">Background</h2>
                        <div class="mx-auto mb-4 rounded-pill bg-orange opacity-25" style="width: 60px; height: 3px;"></div>
                        <p class="text-muted" style="text-align: center; line-height: 1.8;" data-i18n="partner.background_desc">
                            Initiated in August 2024, the Village Assistance Program in Tabanan—delivered in
                            partnership with PT GO Circular Solution Indonesia (GoSirk) as the local implementing
                            partner of the CLOCC Program—aims to strengthen the capacity of village
                            governments in advancing integrated and sustainable waste management in line
                            with the Tabanan District Waste Management Master Plan (RIPS). The program was
                            formally launched through the signing of a Memorandum of Understanding (MoU)
                            between GoSirk and the governments of the three pilot villages: Bengkel, Dauh Peken,
                            and Wongaya Gede.
                        </p>
                    </div>
                </div>

            <!-- Village Part -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-2" style="color: var(--gi-dark);" data-i18n="partner.pilot_villages_title">Desa Percontohan</h2>
                    <div class="mx-auto mb-5 rounded-pill bg-orange opacity-25" style="width: 60px; height: 3px;"></div>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <?php if (!empty($data['villages'])) : ?>
                    <?php foreach ($data['villages'] as $v) : ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border border-1 shadow-sm rounded-4 overflow-hidden border-top-orange-3" style="border-radius: 20px !important; transition: all 0.3s ease;">
                                <div class="position-relative overflow-hidden">
                                    <img src="<?= ASSETS_URL ?>img/<?= $v->image ?>" class="card-img-top" alt="<?= $v->name_id ?>" style="height: 280px; object-fit: cover;">
                                    <div class="card-img-overlay d-flex align-items-end p-0" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%);">
                                        <div class="w-100 p-4 text-center">
                                            <h5 class="fw-bold mb-0 text-white text-uppercase tracking-wider" 
                                                data-lang-id="<?= $v->name_id ?>" 
                                                data-lang-en="<?= $v->name_en ?>">
                                                <?= $v->name_id ?>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted italic" data-i18n="partner.empty_villages">Data desa percontohan belum tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Button Part -->
            <!-- <div class="text-center mt-5 pt-3">
                <a href="#" class="btn btn-gi-primary shadow-sm" data-i18n="partner.view_more">
                    Selengkapnya
                </a>
            </div> -->
        </div>
    </section>

    <!-- TARGET PROGRAM -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="target-container shadow-sm bg-white border-top-orange-3 rounded-4 overflow-hidden">
                <div class="bg-orange-soft p-3 mb-4">
                    <h5 class="fw-bold mb-0 text-center text-orange" data-i18n="partner.target_title">TARGET PROGRAM</h5>
                </div>
                <ul class="list-unstyled d-flex flex-column gap-3">
                    <li class="d-flex gap-3">
                        <span class="material-symbols-outlined text-orange" style="font-size: 1.2rem;">check_circle</span>
                        <span data-i18n="partner.target_item1">Target 100% tingkat pengumpulan sampah...</span>
                    </li>
                    <li class="d-flex gap-3">
                        <span class="material-symbols-outlined text-orange" style="font-size: 1.2rem;">check_circle</span>
                        <span data-i18n="partner.target_item2">Penurunan tingkat kebocoran sampah ke lingkungan (waste leakage rate).</span>
                    </li>
                    <li class="d-flex gap-3">
                        <span class="material-symbols-outlined text-orange" style="font-size: 1.2rem;">check_circle</span>
                        <span data-i18n="partner.target_item3">Peningkatan daur ulang (recycling rate).</span>
                    </li>
                    <li class="d-flex gap-3">
                        <span class="material-symbols-outlined text-orange" style="font-size: 1.2rem;">check_circle</span>
                        <span data-i18n="partner.target_item4">Penurunan timbulan sampah residu ke TPA yang secara jauhnya berimplikasi pada pencegahan kebocoran sampah plastik ke laut.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>


    <!-- IMPACT -->
    <section class="py-5 bg-white overflow-hidden">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subheader" data-i18n="partner.impact.title">Impact Metrics CLOCC</span>
                <h4 class="fw-bold mb-2" data-i18n="partner.impact.subtitle">Wrap-up of Program (Per September 2025)</h4>
                <div class="mx-auto mt-2 rounded-pill bg-orange opacity-25" style="width: 50px; height: 3px;"></div>
            </div>

            <!-- Accordion Impact -->
            <div class="accordion accordion-gi" id="impactAccordion">
                <?php
                // Group impacts by section
                $wp_groups = [];
                if (!empty($data['impacts'])) {
                    foreach ($data['impacts'] as $imp) {
                        $wp_groups[$imp->section][] = $imp;
                    }
                }
                
                // Sort keys to ensure WP 1 comes before WP 2, etc.
                ksort($wp_groups);

                $first = true;
                foreach($wp_groups as $wp_section => $items):
                    // Generate a slug-safe ID for the accordion
                    $wp_id = str_replace(' ', '', $wp_section);
                    // Get section title from the first item
                    $section_title_id = $items[0]->section_title_id ?: $wp_section;
                    $section_title_en = $items[0]->section_title_en ?: $wp_section;
                ?>
                <div class="accordion-item shadow-sm mb-3 border-0 rounded-4 overflow-hidden">
                    <h2 class="accordion-header" id="heading<?= $wp_id ?>">
                        <button class="accordion-button <?= !$first ? 'collapsed' : '' ?> fw-bold py-4 px-4" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse<?= $wp_id ?>" 
                                aria-expanded="<?= $first ? 'true' : 'false' ?>" 
                                aria-controls="collapse<?= $wp_id ?>"
                                style="background: white; color: var(--gi-dark);">
                            <span data-lang-id="<?= preg_replace('/WP\s*\d+:\s*/i', '', $section_title_id) ?>" data-lang-en="<?= preg_replace('/WP\s*\d+:\s*/i', '', $section_title_en) ?>">
                                <?= preg_replace('/WP\s*\d+:\s*/i', '', $section_title_id) ?>
                            </span>
                        </button>
                    </h2>
                    <div id="collapse<?= $wp_id ?>" 
                         class="accordion-collapse collapse <?= $first ? 'show' : '' ?>" 
                         aria-labelledby="heading<?= $wp_id ?>" 
                         data-bs-parent="#impactAccordion">
                        <div class="accordion-body bg-light-subtle p-4">
                            <div class="row g-4 justify-content-center">
                                <?php foreach($items as $imp): ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="metric-card bg-white">
                                        <div class="metric-value"><?= $imp->value ?></div>
                                        <div class="metric-unit"><?= $imp->unit ?></div>
                                        <div class="metric-label" data-lang-id="<?= $imp->label_id ?>" data-lang-en="<?= $imp->label_en ?>">
                                            <?= $imp->label_id ?>
                                        </div>
                                        <div class="metric-note" data-lang-id="<?= $imp->note_id ?>" data-lang-en="<?= $imp->note_en ?>">
                                            <?= $imp->note_id ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $first = false; endforeach; ?>

                <?php if (empty($wp_groups)) : ?>
                    <div class="text-center py-5 text-muted" data-i18n="common.no_data">No impact data available for this page.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>


    <!-- PORTFOLIO SECTION -->
    <section id="portfolio" class="py-5 bg-light">
        <div class="container py-5">
            <div class="d-flex align-items-center justify-content-between mb-5">
                <div>
                    <span class="section-subheader" data-i18n="partner.portfolio_badge">Portfolio</span>
                    <h2 class="section-title display-6 mb-2" data-i18n="partner.portfolio_title">Kegiatan Pendampingan Desa Program Clocc</h2>
                    <div class="rounded-pill bg-orange opacity-25" style="width: 60px; height: 3px;"></div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-orange rounded-circle p-2 portfolio-prev" style="width: 45px; height: 45px; border-color: var(--gosirk-orange); color: var(--gosirk-orange);">
                        <span class="material-symbols-outlined v-align-middle">chevron_left</span>
                    </button>
                    <button class="btn btn-orange rounded-circle p-2 portfolio-next" style="width: 45px; height: 45px; background: var(--gosirk-orange); border-color: var(--gosirk-orange); color: white;">
                        <span class="material-symbols-outlined v-align-middle">chevron_right</span>
                    </button>
                </div>
            </div>

            <div class="swiper portfolio-slider">
                <div class="swiper-wrapper">
                    <?php if (!empty($data['portfolios'])): ?>
                        <?php foreach ($data['portfolios'] as $portfolio): ?>
                            <div class="swiper-slide h-auto">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden border-top-orange-3">
                                    <div style="height: 200px; overflow: hidden;">
                                        <?php if ($portfolio->cover_image) : ?>
                                            <img src="<?= ASSETS_URL ?>img/portfolio/<?= $portfolio->cover_image ?>" class="w-100 h-100 object-fit-cover" alt="<?= $portfolio->title_id ?>">
                                        <?php else : ?>
                                            <div class="w-100 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center">
                                                <i class="<?= $portfolio->icon_name ?: 'fas fa-folder' ?> text-muted opacity-25" style="font-size: 80px;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body p-4 bg-white d-flex flex-column">
                                        <div class="flex-grow-1">
                                            <span class="badge bg-soft-orange text-orange mb-2" style="color: var(--gosirk-orange); background-color: rgba(255, 143, 86, 0.1);">
                                                <?= $portfolio->subtitle_id ?>
                                            </span>
                                            <h5 class="fw-bold mb-2 text-truncate-2" data-lang-id="<?= strip_tags($portfolio->title_id ?? '') ?>" data-lang-en="<?= strip_tags($portfolio->title_en ?? '') ?>">
                                                <?= $portfolio->title_id ?>
                                            </h5>
                                            <div class="text-muted small mb-0 text-truncate-3" data-lang-id="<?= strip_tags($portfolio->description_id ?? '') ?>" data-lang-en="<?= strip_tags($portfolio->description_en ?? '') ?>">
                                                <?= strip_tags($portfolio->description_id) ?>
                                            </div>
                                        </div>
                                        <div class="mt-3 text-end">
                                            <a href="<?= BASE_URL ?>portfolio/detail/<?= $portfolio->id ?>" class="btn btn-outline-orange rounded-pill btn-sm px-4" style="border-color: var(--gosirk-orange); color: var(--gosirk-orange);" data-i18n="partner.read_more">Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide w-100">
                            <div class="text-center py-5 w-100 bg-white rounded-4 border border-orange-soft">
                                <span class="material-symbols-outlined text-orange opacity-25" style="font-size: 4rem;">folder_off</span>
                                <p class="text-muted mt-3 mb-0" data-i18n="partner.portfolio_empty">Data portofolio belum tersedia.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination mt-4 position-relative"></div>
            </div>
        </div>
    </section>
    
    <!-- LOGO PARTNERS SLIDER (Home Style) -->
    <section class="section bg-light pb-5">
      <div class="container">
        <h3 class="text-center fw-bold mb-5" data-i18n="partner.implementation.partners_title">MITRA DAN JEJARING KAMI</h3>
        <div class="swiper logo-slider">
          <div class="swiper-wrapper align-items-center">
            <?php 
            $partners_data = $data['partners'] ?? [];
            $foundContribution = false;
            if (!empty($partners_data)) : 
              foreach ($partners_data as $ptr) : 
                if (($ptr->category ?? '') == 'contribution') :
                  $foundContribution = true;
            ?>
                <div class="swiper-slide text-center">
                  <?php 
                    $logoFile = $ptr->logo;
                    $logoUrl = ASSETS_URL . 'img/partners/' . $logoFile;
                    echo '<img src="' . $logoUrl . '" alt="' . $ptr->name . '" style="max-height: 80px; width: auto;" onerror="this.src=\'' . ASSETS_URL . 'img/Logo-GoSirk-01.png\'; this.style.opacity=\'0.3\';">';
                  ?>
                </div>
            <?php 
                endif;
              endforeach; 
            endif; 
            
            if (!$foundContribution) : ?>
              <div class="swiper-slide text-center text-muted small" data-i18n="common.no_partners">No contribution partners yet</div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- HIGHLIGHTS (Sorotan): managed in Admin > Sorotan Implementasi -->
    <?php
    $highlightsSection = $data['highlights_section'] ?? null;
    if ($highlightsSection && (int) $highlightsSection->is_active === 1) {
        $highlightItems = json_decode($highlightsSection->content_id ?: '[]', true) ?: [];
        $highlightHeading = '<div class="text-center mb-5">'
            . '<span class="section-subheader" data-i18n="partner.highlights_subheader">Dokumentasi</span>'
            . '<h2 class="section-title display-6" data-i18n="partner.highlights_title">Sorotan</h2>'
            . '</div>';
        require dirname(__DIR__) . '/partials/highlights.php';
    }
    ?>

    <!-- TESTIMONIALS SECTION -->
    <!-- <section class="testimonial-section py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <span class="section-subheader" data-i18n="partner.testimonials.title">Bukti Kontribusi Kami</span>
                <h3 class="fw-bold" data-i18n="partner.testimonials.title">Apa Kata Mitra Kami?</h3>
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

    <!-- FAQ SECTION -->
    <!-- <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <span class="section-subheader" data-i18n="partner.faq.title">FAQ Implementasi</span>
                        <h3 class="fw-bold" data-i18n="partner.faq.title">Pertanyaan Umum</h3>
                    </div>
                    
                    <div class="accordion accordion-gi" id="faqAccordion">
                        <?php if (!empty($faqs)) : ?>
                            <?php foreach ($faqs as $index => $faq) : ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#ifaq<?= $faq->id ?>">
                                            <span data-lang-id="<?= $faq->question_id ?>" data-lang-en="<?= $faq->question_en ?>">
                                                <?= $faq->question_id ?>
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="ifaq<?= $faq->id ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <div data-lang-id="<?= $faq->answer_id ?>" data-lang-en="<?= $faq->answer_en ?>">
                                                <?= nl2br($faq->answer_id) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="text-center p-4">
                                <p class="text-muted" data-i18n="partner.faq.empty">FAQ belum tersedia untuk kategori ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- CTA SECTION - Updated to Partnership Style -->
    <section class="partnership-cta py-5 mb-5">
        <div class="container">
            <div class="cta-banner rounded-5 overflow-hidden position-relative">
                <div class="cta-overlay" style="background: linear-gradient(90deg, rgba(255, 143, 86, 0.95) 0%, rgba(229, 118, 61, 0.9) 100%);"></div>
                <div class="row align-items-center position-relative z-3 p-5">
                    <div class="col-lg-8 text-center text-lg-start">
                        <h2 class="display-6 fw-bold text-white mb-3 text-uppercase" data-i18n="partner.cta_title">Siap Mengembangkan Program Bersama?</h2>
                        <p class="text-white-50 mb-4 fs-5" data-i18n="partner.cta_subtitle">Mulai diskusi strategis Anda hari ini untuk menciptakan dampak yang berkelanjutan.</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="<?= BASE_URL ?>contact" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow border-0" 
                           style="background-color: white; color: var(--gosirk-orange);" data-i18n="partner.cta_button">
                            Ajukan Kerja Sama
                        </a>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 p-4 opacity-25">
                     <img src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="GoSirk" style="max-height: 80px; filter: brightness(0) invert(1);">
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
        // Initialize Portfolio Slider
        new Swiper(".portfolio-slider", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".portfolio-next",
                prevEl: ".portfolio-prev",
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
                delay: 4000,
                disableOnInteraction: false,
            }
        });

        // Initialize Logo Slider (Home Style)
        new Swiper(".logo-slider", {
            slidesPerView: 2,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                576: { slidesPerView: 3 },
                768: { slidesPerView: 4 },
                1024: { slidesPerView: 5 },
            }
        });

        // Toggle Impact Button Text
        const impactToggle = document.getElementById('impactToggleButton');
        const impactCollapse = document.getElementById('moreImpacts');
        
        if (impactToggle && impactCollapse) {
            impactCollapse.addEventListener('show.bs.collapse', function () {
                impactToggle.setAttribute('data-i18n', 'partner.view_less_impact');
                if (typeof updateTranslations === 'function') updateTranslations();
            });
            
            impactCollapse.addEventListener('hide.bs.collapse', function () {
                impactToggle.setAttribute('data-i18n', 'partner.view_more_impact');
                if (typeof updateTranslations === 'function') updateTranslations();
            });
        }
    });
</script>
