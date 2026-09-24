<style>
    .portfolio-detail-hero {
        padding: 0;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }
    .status-badge {
        background-color: #e8f0fe;
        color: #0d6efd;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 6px 16px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-bottom: 15px;
    }
    .portfolio-title {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    .portfolio-meta {
        color: #666;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 30px;
    }
    .impact-metrics-row {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        margin-top: 20px;
    }
    .metric-item .metric-value {
        font-weight: 800;
        font-size: 1.5rem;
        color: #0d4a7c;
        display: block;
    }
    .metric-item .metric-label {
        font-size: 0.75rem;
        color: #777;
        line-height: 1.2;
        display: block;
    }
    
    .project-logos-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
    }
    .project-logo-item {
        height: 35px;
    }
    .project-logo-item img {
        height: 100%;
        width: auto;
        object-fit: contain;
    }
    .hero-image-container {
        border-radius: 0;
        overflow: hidden;
        height: 540px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 18px 45px rgba(0,0,0,0.08);
        position: relative;
    }
    .hero-image-container::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.55), rgba(0,0,0,0.28) 45%, rgba(0,0,0,0.08) 100%);
        pointer-events: none;
        z-index: 1;
    }
    .hero-image-container .breadcrumb-overlay {
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        z-index: 3;
        background: transparent;
        border-radius: 0;
        padding: 0.75rem 1rem;
        margin-bottom: 0;
    }
    .hero-image-container .breadcrumb-overlay .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(0,0,0,0.5);
    }
    .hero-image-container .breadcrumb-overlay a {
        color: #343a40;
    }
    .hero-image-container .breadcrumb-overlay .active {
        color: #0d6efd;
        font-weight: 700;
    }
    .hero-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .portfolio-hero-content {
        max-width: 900px;
        margin: 35px auto 0;
        text-align: center;
        padding-bottom: 45px;
    }
    
    .section-header-sm {
        font-weight: 800;
        font-size: 1.2rem;
        text-transform: uppercase;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }
    
    .peran-box {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 15px;
        border-left: 5px solid #0d4a7c;
    }
    
    .target-container {
        background-color: #fff;
        padding: 40px;
        border-radius: 20px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
    }
    
    .impact-grid-item {
        text-align: center;
        padding: 20px;
    }
    .impact-value-blue {
        color: #0d6efd;
        font-weight: 800;
        font-size: 1.8rem;
        display: block;
    }
    .impact-label-gray {
        color: #666;
        font-size: 0.8rem;
    }
    
    .approach-box {
        background-color: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .portfolio-about-section {
        background:
            radial-gradient(circle at 8% 20%, rgba(13, 74, 124, 0.08), transparent 34%),
            linear-gradient(135deg, #ffffff 0%, #f4f9ff 100%);
    }

    .portfolio-approach-section {
        background:
            radial-gradient(circle at 88% 12%, rgba(34, 197, 94, 0.10), transparent 32%),
            linear-gradient(135deg, #f7fbff 0%, #f3fbf6 100%);
    }

    .portfolio-highlights-section {
        background:
            radial-gradient(circle at 12% 18%, rgba(13, 110, 253, 0.10), transparent 30%),
            linear-gradient(180deg, #f8fafc 0%, #eef6ff 100%);
    }
    
    .highlight-card {
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        height: 300px;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        cursor: pointer;
    }
    .highlight-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 45px rgba(0,0,0,0.15) !important;
    }
    .highlight-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .highlight-card:hover img {
        transform: scale(1.1);
    }
    .highlight-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 40%, transparent 100%);
        display: flex;
        align-items: flex-end;
        padding: 25px;
        color: white;
        opacity: 0.9;
        transition: all 0.4s ease;
    }
    .highlight-card:hover .highlight-overlay {
        opacity: 1;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
    }
    .highlight-overlay p {
        transform: translateY(5px);
        transition: transform 0.4s ease;
    }
    .highlight-card:hover .highlight-overlay p {
        transform: translateY(0);
    }

    .portfolio-video-block {
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 1.25rem;
        background: #000;
        min-height: 250px;
        box-shadow: 0 18px 45px rgba(0,0,0,0.12);
    }
    .portfolio-video-block iframe {
        width: 100%;
        height: 100%;
        min-height: 320px;
        border: 0;
    }
    .portfolio-video-block::before {
        content: '';
        display: block;
        padding-top: 56.25%;
    }
    .portfolio-video-block iframe {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
    }

    .cta-partnership {
        background:
            linear-gradient(135deg, rgba(8, 48, 84, 0.94) 0%, rgba(13, 74, 124, 0.90) 42%, rgba(22, 128, 83, 0.88) 100%),
            linear-gradient(180deg, rgba(0,0,0,0.16), rgba(0,0,0,0.32)),
            url('<?= ASSETS_URL ?>img/partnership-bg.jpg') center/cover;
        padding: 100px 0;
        color: white;
        text-align: center;
    }

    /* Multi-language content handling */
    [data-lang-id], [data-lang-en] {
        transition: opacity 0.3s ease;
    }
</style>

<!-- SECTION 1: HERO (Title, Cover, Stats) -->
<section class="portfolio-detail-hero">
    <div class="container-fluid px-0">
        <div class="hero-image-container">
            <nav aria-label="breadcrumb" class="breadcrumb-overlay">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none text-muted" data-i18n="breadcrumb.home">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>partnership" class="text-decoration-none text-muted" data-i18n="breadcrumb.portfolio">Portfolio</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page" data-i18n="portfolio.detail_title">Detail Proyek</li>
                </ol>
            </nav>
            <?php if($portfolio->cover_image): ?>
                <img src="<?= ASSETS_URL ?>img/portfolio/<?= $portfolio->cover_image ?>" alt="<?= $portfolio->title_id ?>">
            <?php else: ?>
                <div class="display-1 text-muted"><i class="<?= $portfolio->icon_name ?: 'fas fa-project-diagram' ?>"></i></div>
            <?php endif; ?>
        </div>

        <div class="portfolio-hero-content">
            <span class="status-badge" data-lang-id="<?= strtoupper($portfolio->partner_type) ?>" data-lang-en="<?= strtoupper($portfolio->partner_type) ?>">
                <?= strtoupper($portfolio->partner_type) ?>
            </span>
            <h1 class="portfolio-title" data-lang-id="<?= htmlspecialchars($portfolio->title_id) ?>" data-lang-en="<?= htmlspecialchars($portfolio->title_en) ?>">
                <?= $portfolio->title_id ?>
            </h1>
            
            <?php 
            $project_logos = json_decode($portfolio->project_logos ?: '[]', true);
            if (!empty($project_logos)): 
            ?>
            <div class="project-logos-wrapper">
                <?php foreach($project_logos as $logo): ?>
                <div class="project-logo-item">
                    <img src="<?= ASSETS_URL ?>img/portfolio/<?= $logo ?>" alt="Partner Logo">
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- SECTION 2: Description -->
<section class="py-5 portfolio-about-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <h4 class="section-header-sm" data-i18n="portfolio.about">Tentang</h4>
                <div class="text-muted" data-lang-id="<?= htmlspecialchars($portfolio->detail_content_id) ?>" data-lang-en="<?= htmlspecialchars($portfolio->detail_content_en) ?>">
                    <?= $portfolio->detail_content_id ?>
                </div>
            </div>

        </div>
    </div>
</section>

<?php 
$approaches = json_decode($portfolio->approach_id ?: '[]', true);
if (!empty($approaches)): 
?>
<section class="py-5 portfolio-approach-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <h4 class="section-header-sm" data-i18n="portfolio.approach">Pendekatan</h4>
                <p class="text-muted" data-i18n="portfolio.approach_desc">
                    Pendekatan strategis yang kami terapkan untuk memastikan keberhasilan dan keberlanjutan program di setiap tahapan.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="approach-id-container w-100" data-lang-id>
                        <div class="row g-4">
                            <?php 
                            $approaches_id = json_decode($portfolio->approach_id ?: '[]', true);
                            foreach($approaches_id as $a): 
                            ?>
                            <div class="col-md-12">
                                <div class="approach-box">
                                    <h6 class="fw-bold mb-2"><?= $a['title'] ?></h6>
                                    <p class="small text-muted mb-0"><?= $a['desc'] ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="approach-en-container w-100" data-lang-en style="display: none;">
                        <div class="row g-4">
                            <?php 
                            $approaches_en = json_decode($portfolio->approach_en ?: '[]', true);
                            $display_approaches = !empty($approaches_en) ? $approaches_en : $approaches_id;
                            foreach($display_approaches as $a): 
                            ?>
                            <div class="col-md-12">
                                <div class="approach-box">
                                    <h6 class="fw-bold mb-2"><?= $a['title'] ?></h6>
                                    <p class="small text-muted mb-0"><?= $a['desc'] ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
$highlights = json_decode($portfolio->highlights ?: '[]', true);
if (!empty($highlights)): 
?>
<section class="py-5 portfolio-highlights-section">
    <div class="container">
        <h4 class="text-center section-header-sm mb-5" data-i18n="portfolio.highlights">Sorotan</h4>
        <?php
            $youtube_source = $portfolio->video_url ?? $portfolio->video_link ?? $portfolio->video_source ?? $portfolio->source_video ?? '';
            $youtube_embed = '';
            if (!empty($youtube_source)) {
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_source, $match)) {
                    $youtube_embed = 'https://www.youtube.com/embed/' . $match[1];
                }
            }
        ?>
        <?php if (!empty($youtube_embed)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="portfolio-video-block">
                    <iframe src="<?= $youtube_embed ?>?rel=0&showinfo=0" title="Video Proyek" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="row g-4">
            <?php foreach($highlights as $h): ?>
            <div class="col-lg-4 col-md-6">
                <div class="highlight-card shadow-sm">
                    <img src="<?= ASSETS_URL ?>img/portfolio/<?= $h['image'] ?>" alt="Highlight">
                    <div class="highlight-overlay">
                        <p class="small mb-0" data-lang-id="<?= htmlspecialchars($h['caption'] ?? '') ?>" data-lang-en="<?= htmlspecialchars($h['caption'] ?? '') ?>">
                            <?= $h['caption'] ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SECTION 6: CTA (Call to Action) -->
<section class="cta-partnership">
    <div class="container">
        <h2 class="fw-bold mb-3" data-i18n="portfolio.cta_title">MARI BEKERJA SAMA UNTUK<br>SOLUSI PERSAMPAHAN BERKELANJUTAN</h2>
        <p class="mb-4 opacity-75" data-i18n="portfolio.cta_desc">Untuk info lebih lanjut, hubungi kami sekarang!</p>
        <a href="<?= BASE_URL ?>contact" class="btn btn-warning rounded-pill px-5 py-3 fw-bold" data-i18n="portfolio.cta_button">Hubungi Kami</a>
    </div>
</section>

<script>
    // Specific script for handling complex dynamic content in portfolio detail
    document.addEventListener('languageChanged', function(e) {
        const lang = e.detail.language;
        
        // Handle Target Project list specifically if it's complex
        // Handle Target Project list specifically
        const targetContainer = document.querySelector('.targets-content');
        if (targetContainer) {
            const raw = lang === 'en' ? targetContainer.getAttribute('data-lang-en') : targetContainer.getAttribute('data-lang-id');
            if (raw) {
                // Split by newline or <br> to handle different formats
                const lines = raw.split(/[\n\r]|<br\s*\/?>/gi).filter(l => l.trim() !== '');
                let html = '<ul class="list-unstyled d-flex flex-column gap-3">';
                lines.forEach(line => {
                    // Strip tags if any, but kept simple here
                    const cleanLine = line.replace(/<[^>]*>?/gm, '').trim();
                    if (cleanLine) {
                        html += `<li class="d-flex gap-3"><i class="fas fa-check-circle text-primary mt-1"></i><span>${cleanLine}</span></li>`;
                    }
                });
                html += '</ul>';
                targetContainer.innerHTML = html;
            }
        }

        // Toggle visibility for JSON-based structures
        document.querySelectorAll('[data-lang-id]').forEach(el => {
            if (el.classList.contains('approach-id-container') || el.classList.contains('impact-metrics-row-id')) {
                el.style.display = lang === 'id' ? 'block' : 'none';
            }
        });
        document.querySelectorAll('[data-lang-en]').forEach(el => {
            if (el.classList.contains('approach-en-container') || el.classList.contains('impact-metrics-row-en')) {
                el.style.display = lang === 'en' ? 'block' : 'none';
            }
        });
    });
</script>
