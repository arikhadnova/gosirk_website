<!-- Hero Section -->
<section class="about-hero hero-full" style="background-image: url('<?= PageImages::attr('about.hero_bg') ?>');">
    <div class="container">
        <h1 class="hero-full-title" data-i18n="about.hero.title">GoSirk | Solusi Untuk Indonesia</h1>
        <div class="hero-full-actions">
            <a href="#about" class="btn btn-light" data-i18n="about.hero.cta_about">ABOUT US</a>
            <a href="<?= BASE_URL ?>contact" class="btn btn-outline-light" data-i18n="about.hero.cta_contact">CONTACT US</a>
        </div>
    </div>
</section>

<!-- Tentang Kami -->
<?php
$aboutSection = $data['about_section'] ?? null;
$aboutBadgeId = $aboutSection->badge_id ?? 'Tentang Kami';
$aboutBadgeEn = $aboutSection->badge_en ?? 'About Us';
$aboutTitleId = $aboutSection->title_id ?? 'Membangun Masa Depan Berkelanjutan';
$aboutTitleEn = $aboutSection->title_en ?? 'Building a Sustainable Future';
$aboutContentId = $aboutSection->content_id ?? 'PT Gocircular Solutions Indonesia (GoSirk) adalah perusahaan swasta dengan orientasi bisnis sosial yang kuat, didedikasikan untuk mengembangkan solusi inovatif dan ramah lingkungan dalam pengelolaan sampah.';
$aboutContentEn = $aboutSection->content_en ?? 'PT Gocircular Solutions Indonesia (GoSirk) is a private company with a strong social business orientation, dedicated to developing innovative and environmentally friendly waste management solutions.';
$aboutContent2Id = $aboutSection->content_2_id ?? 'Kami berkomitmen untuk menciptakan sistem pengelolaan sampah yang berkelanjutan melalui implementasi bisnis sirkular dan program-program yang memberikan manfaat bagi lingkungan serta memberdayakan usaha lokal di sektor pengelolaan sampah.';
$aboutContent2En = $aboutSection->content_2_en ?? 'We are committed to creating sustainable waste management systems through circular business implementation and programs that benefit the environment while empowering local waste management businesses.';
$aboutContent3Id = $aboutSection->content_3_id ?? 'Kami percaya pada kekuatan kolaborasi dan komunitas, bekerja bersama dengan usaha-usaha lokal untuk mendorong pertumbuhan, menciptakan lapangan kerja, dan meningkatkan standar praktik pengelolaan sampah.';
$aboutContent3En = $aboutSection->content_3_en ?? 'We believe in the power of collaboration and community, working together with local enterprises to encourage growth, create jobs, and improve waste management practice standards.';
$aboutImage = $aboutSection->image ?? 'about-2.jpg';
$aboutImageUrl = $aboutImage && filter_var($aboutImage, FILTER_VALIDATE_URL) ? $aboutImage : ASSETS_URL . 'img/' . $aboutImage;
?>
<?php if (!$aboutSection || ((int) ($aboutSection->is_active ?? 1)) === 1): ?>
<section id="about" class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-image-wrapper">
                    <img loading="lazy" decoding="async" src="<?= htmlspecialchars($aboutImageUrl, ENT_QUOTES) ?>" class="about-image" alt="About Presentation">
                    <div class="logo-overlay m-3">
                         <img loading="lazy" decoding="async" src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="Logo">
                    </div>
                    <div class="decor-circle"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="text-primary fw-bold text-uppercase mb-3" data-lang-id="<?= htmlspecialchars($aboutBadgeId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutBadgeEn, ENT_QUOTES) ?>"><?= $aboutBadgeId ?></h5>
                <h2 class="fw-bold mb-4 display-6" data-lang-id="<?= htmlspecialchars($aboutTitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutTitleEn, ENT_QUOTES) ?>"><?= $aboutTitleId ?></h2>
                <p class="text-secondary lead fs-6" data-lang-id="<?= htmlspecialchars($aboutContentId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContentEn, ENT_QUOTES) ?>">
                    <?= $aboutContentId ?>
                </p>
                <p class="text-secondary fs-6" data-lang-id="<?= htmlspecialchars($aboutContent2Id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContent2En, ENT_QUOTES) ?>">
                    <?= $aboutContent2Id ?>
                </p>
                <p class="text-secondary fs-6" data-lang-id="<?= htmlspecialchars($aboutContent3Id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContent3En, ENT_QUOTES) ?>">
                    <?= $aboutContent3Id ?>
                </p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Visi -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary" data-i18n="about.vision_title">VISI</h2>
        </div>
        <div class="card border-0 shadow-lg rounded-4 p-5 mx-auto bg-white text-center position-relative" style="max-width: 900px;">
             <!-- Decorative quotes -->
            <span class="position-absolute top-0 start-0 m-3 text-warning fs-1 opacity-25"><i class="fas fa-quote-left"></i></span>
            <span class="position-absolute bottom-0 end-0 m-3 text-warning fs-1 opacity-25"><i class="fas fa-quote-right"></i></span>
            
            <p class="fs-5 fw-medium text-dark lh-base m-0" data-i18n="about.vision.desc">
                "Menjadi perusahaan terdepan dan rujukan dalam pendampingan pengelolaan sampah daerah, sebagai pilar ekosistem persampahan nasional untuk mewujudkan Indonesia Bersih dan Sejahtera."
            </p>
        </div>
    </div>
</section>

<!-- Misi -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary" data-i18n="about.mission.title">MISI</h2>
        </div>
        <div class="row g-4">
            <!-- Misi 1 -->
            <div class="col-md-3">
                <div class="card mission-card p-4">
                    <div class="mission-number">1</div>
                    <p class="text-secondary small mb-0" data-i18n="about.mission.m1">Perusahaan yang memberikan solusi inovatif bagi masyarakat untuk peningkatan pengelolaan pelayanan persampahan di tingkat daerah.</p>
                </div>
            </div>
            <!-- Misi 2 -->
            <div class="col-md-3">
                <div class="card mission-card p-4">
                    <div class="mission-number">2</div>
                    <p class="text-secondary small mb-0" data-i18n="about.mission.m2">Perusahaan yang memiliki kapabilitas tinggi dan akuntabel dalam fasilitasi pendampingan & manajemen industri termasuk hilirisasi pengelolaan sampah.</p>
                </div>
            </div>
            <!-- Misi 3 -->
            <div class="col-md-3">
                <div class="card mission-card p-4">
                    <div class="mission-number">3</div>
                    <p class="text-secondary small mb-0" data-i18n="about.mission.m3">Perusahaan yang menerapkan seluruh operasionalnya sesuai dengan ekosistem pengelolaan sampah dan mematuhi prinsip keberlanjutan.</p>
                </div>
            </div>
            <!-- Misi 4 -->
            <div class="col-md-3">
                <div class="card mission-card p-4">
                    <div class="mission-number">4</div>
                    <p class="text-secondary small mb-0" data-i18n="about.mission.m4">Menjadi salah satu institusi rujukan nasional sebagai pusat pembelajaran pengelolaan sampah daerah.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-5 bg-light">
    <div class="container text-center py-4">
         <h2 class="fw-bold mb-5 text-primary text-uppercase" data-i18n="about.values.title">Nilai-Nilai Utama Kami</h2>
         <div class="row g-5 justify-content-center">
             <!-- Committed -->
             <div class="col-md-4">
                 <div class="p-4 rounded-4 bg-white h-100 shadow-sm border-bottom border-4 border-primary">
                     <i class="fas fa-handshake fa-3x mb-4 text-primary"></i>
                     <h4 class="fw-bold mb-3 text-dark" data-i18n="about.value_committed">COMMITTED</h4>
                     <p class="text-muted small" data-i18n="about.values.committed_desc">Komitmen penuh dalam memberikan solusi yang berdampak dan berkelanjutan bagi pengelolaan sampah dan pemberdayaan masyarakat.</p>
                 </div>
             </div>
              <!-- Dedicated -->
             <div class="col-md-4">
                 <div class="p-4 rounded-4 bg-white h-100 shadow-sm border-bottom border-4 border-primary">
                     <i class="fas fa-heart fa-3x mb-4 text-primary"></i>
                     <h4 class="fw-bold mb-3 text-dark" data-i18n="about.value_dedicated">DEDICATED</h4>
                     <p class="text-muted small" data-i18n="about.values.dedicated_desc">Dedikasi kuat untuk terus belajar, berkembang, dan memberikan kontribusi nyata melalui kerja kolaborasi yang progresif.</p>
                 </div>
             </div>
              <!-- Integrity -->
             <div class="col-md-4">
                 <div class="p-4 rounded-4 bg-white h-100 shadow-sm border-bottom border-4 border-primary">
                     <i class="fas fa-balance-scale fa-3x mb-4 text-primary"></i>
                     <h4 class="fw-bold mb-3 text-dark" data-i18n="about.value_integrity">INTEGRITY</h4>
                     <p class="text-muted small" data-i18n="about.values.integrity_desc">Menjunjung tinggi kejujuran, transparansi, dan tanggung jawab dalam setiap proses kerja serta hubungan dengan mitra dan komunitas.</p>
                 </div>
             </div>
         </div>
    </div>
</section>

<!-- Meet Our Founder -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-5 text-primary text-uppercase" data-i18n="about.founder.title">Meet Our Founder</h2>
        <div class="row justify-content-center g-5">
            <?php if (!empty($founders)) : ?>
                <?php foreach ($founders as $founder) : ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="founder-card">
                            <div class="founder-img-wrapper">
                                <img loading="lazy" decoding="async" src="<?= ASSETS_URL ?>img/<?= $founder->image ?>" class="founder-img" alt="<?= $founder->name ?>">
                            </div>
                            <h5 class="fw-bold mb-1"><?= $founder->name ?></h5>
                            <p class="text-muted mb-3 small" data-lang-id="<?= $founder->role_id ?>" data-lang-en="<?= $founder->role_en ?>">
                                <?= $founder->role_id // Default to ID ?>
                            </p>
                            <?php if (!empty($founder->linkedin_url) && $founder->linkedin_url != '#') : ?>
                                <a href="<?= $founder->linkedin_url ?>" class="linkedin-btn" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                            <?php else : ?>
                                <a href="#" class="linkedin-btn"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                            <div class="mt-3 mx-auto border-top w-50 pt-3">
                                <p class="text-muted small fst-italic" data-lang-id="<?= htmlspecialchars($founder->quote_id) ?>" data-lang-en="<?= htmlspecialchars($founder->quote_en) ?>">
                                    "<?= htmlspecialchars($founder->quote_id) ?>"
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- BANNER -->
<div class="banner-section">
    <img loading="lazy" decoding="async" src="<?= PageImages::attr('about.banner') ?>" alt="Banner GoSirk" class="img-fluid w-100">
</div>
