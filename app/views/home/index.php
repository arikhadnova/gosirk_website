<!-- HERO -->
<?php
  $heroHome = $data['hero'];
  $heroImageData = $heroHome->image ?? 'petugas-baju-biru.png';
  $storedHeroSlides = json_decode($heroImageData, true);
  $heroSlides = is_array($storedHeroSlides) ? $storedHeroSlides : [$heroImageData];

  $heroSlides = array_map(function ($slide) {
      if ($slide && !filter_var($slide, FILTER_VALIDATE_URL)) {
          return ASSETS_URL . 'img/' . $slide;
      }
      return $slide;
  }, $heroSlides);

  if (!is_array($storedHeroSlides)) {
      $heroSlides[] = ASSETS_URL . 'img/banner-1.png';
      $heroSlides[] = ASSETS_URL . 'img/banner-2.png';
  }

  $heroSlides = array_slice(array_unique(array_filter($heroSlides)), 0, 5);
  $heroTransition = in_array(($data['hero_transition'] ?? 'slide'), ['slide', 'fade']) ? $data['hero_transition'] : 'slide';
?>
<section class="hero-home hero-full">
  <div class="hero-home-slider hero-home-slider-<?= $heroTransition ?>" aria-hidden="true">
    <?php foreach ($heroSlides as $index => $slide) : ?>
      <div class="hero-home-slide <?= $index === 0 ? 'active' : '' ?>" style="background-image: url('<?= htmlspecialchars($slide, ENT_QUOTES) ?>');"></div>
    <?php endforeach; ?>
  </div>
  <div class="container">
    <h1 class="hero-full-title" data-lang-id="<?= $heroHome->title_id ?>" data-lang-en="<?= $heroHome->title_en ?>" data-i18n-html="true">
      <?= $heroHome->title_id ?>
    </h1>
    <p class="hero-full-lead" data-lang-id="<?= $heroHome->subtitle_id ?>" data-lang-en="<?= $heroHome->subtitle_en ?>">
      <?= $heroHome->subtitle_id ?>
    </p>
    <div class="hero-full-actions">
      <a href="#services" class="btn btn-light" data-i18n="home.hero.cta_services">Lihat Layanan</a>
      <a href="#portfolio" class="btn btn-outline-light" data-i18n="home.hero.cta_portfolio">Portofolio</a>
      <a href="<?= BASE_URL ?>contact" class="btn btn-outline-light" data-i18n="home.hero.cta_contact">Hubungi Kami</a>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const heroSlides = document.querySelectorAll('.hero-home-slide');
    if (heroSlides.length <= 1) return;

    let activeHeroSlide = 0;
    setInterval(function() {
      const previousHeroSlide = activeHeroSlide;
      heroSlides[previousHeroSlide].classList.remove('active', 'previous');
      activeHeroSlide = (activeHeroSlide + 1) % heroSlides.length;
      heroSlides[previousHeroSlide].classList.add('previous');
      heroSlides[activeHeroSlide].classList.add('active');

      setTimeout(function() {
        heroSlides[previousHeroSlide].classList.remove('previous');
      }, 900);
    }, 5000);
  });
</script>

<!-- IMPACT FOOTPRINT -->
<?php $this->views('partials/impact_footprint', ['impacts' => $data['impacts']]); ?>

<!-- APPROACH -->
<section class="section">
  <div class="container">
    <h3 class="text-center fw-bold mb-5" data-i18n="home.approach.title">PENDEKATAN KAMI</h3>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="approach-card p-4 h-100">
          <span class="material-symbols-outlined" style="font-size: 48px;">docs</span>
          <h5 data-i18n="home.approach.data_driven.title">Berbasis Data</h5>
          <p data-i18n="home.approach.data_driven.desc">Setiap solusi didasarkan pada studi dasar, pengumpulan data, dan penelitian yang tervalidasi.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="approach-card p-4 h-100">
            <span class="material-symbols-outlined" style="font-size: 48px;">group</span>
          <h5 data-i18n="home.approach.community.title">Pemberdayaan Komunitas</h5>
          <p data-i18n="home.approach.community.desc">Memberdayakan komunitas lokal melalui peningkatan kapasitas.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="approach-card p-4 h-100">
            <span class="material-symbols-outlined" style="font-size: 48px;">track_changes</span>
          <h5 data-i18n="home.approach.impact_oriented.title">Berorientasi Pada Dampak</h5>
          <p data-i18n="home.approach.impact_oriented.desc">Berfokus pada hasil yang terukur dan keberlanjutan jangka panjang.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="approach-card p-4 h-100">
            <span class="material-symbols-outlined" style="font-size: 48px;">handshake</span>
          <h5 data-i18n="home.approach.collaborative.title">Kolaboratif</h5>
          <p data-i18n="home.approach.collaborative.desc">Mendorong kolaborasi antar stakeholder untuk menciptakan solusi yang selaras dan berkelanjutan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="section bg-light">
  <div class="container">
    <h2 class="text-center fw-bold mb-2" data-i18n="home.services.title">LAYANAN KAMI</h2>
    <p class="text-center mb-5" data-i18n="home.services.subtitle">Setiap pilar layanan kami bersinergi menghadirkan solusi relevan, aplikatif secara lokal, dan berkelanjutan.</p>
    <div class="row g-4">
      <?php if (!empty($data['services'])) : ?>
        <?php foreach ($data['services'] as $s) : ?>
          <?php 
             $link = '#';
             $lowerTitle = strtolower($s->name_id . ' ' . $s->name_en);
             // Check consultancy first: "strategis" contains "gi", so avoid loose substring matches
             if (preg_match('/konsultansi|consultancy|advisory/', $lowerTitle)) {
                 $link = BASE_URL . 'konsultan';
             } elseif (preg_match('/implementasi|implementation|project development|program development/', $lowerTitle)) {
                 $link = BASE_URL . 'implementasi_partner';
             } elseif (preg_match('/kapasitas|capacity building|institute/', $lowerTitle)) {
                 $link = BASE_URL . 'gi';
             }
          ?>
          <div class="col-md-4">
            <div class="service-card h-100">
              <div class="card-image-wrapper bg-light d-flex align-items-center justify-content-center rounded-top-3" style="height: 200px; overflow: hidden;">
                <?php if (isset($s->image) && $s->image) : ?>
                  <img src="<?= ASSETS_URL ?>img/services/<?= $s->image ?>" alt="<?= $s->name_id ?>" class="w-100 h-100 object-fit-cover">
                <?php else : ?>
                  <img src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="GoSirk" class="opacity-25" style="width: 120px;">
                <?php endif; ?>
              </div>
              <div class="p-4">
                <h5 class="fw-bold" data-lang-id="<?= $s->name_id ?>" data-lang-en="<?= $s->name_en ?>"><?= $s->name_id ?></h5>
                <br>
                <div class="text-muted small" style="text-align: justify;" data-lang-id="<?= $s->description_id ?>" data-lang-en="<?= $s->description_en ?>">
                  <?= $s->description_id ?>
                </div>
                <div class="mt-4">
                  <a href="<?= $link ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4" data-i18n="home.common.read_more">
                    Selengkapnya
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- SERVICE DETAILS -->
<section class="section">
  <div class="container">
    
    <!-- Capacity Building -->
    <div class="mb-5">
      <h3 class="fw-bold mb-4" data-i18n="home.services.cb_title">Layanan Capacity Building</h3>
      <div class="row g-4 service-detail-grid">
          <?php if (!empty($services_cb)) : ?>
            <?php foreach ($services_cb as $item) : ?>
              <div class="col-md-6 col-lg-4">
                <div class="card border border-light shadow-sm h-100 rounded-3">
                  <div class="card-image-wrapper bg-light d-flex align-items-center justify-content-center rounded-top-3" style="height: 200px; overflow: hidden;">
                    <?php if (!empty($item->image)) : ?>
                      <img src="<?= ASSETS_URL ?>img/gi/<?= $item->image ?>" alt="<?= $item->title_id ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                    <?php else : ?>
                      <img src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="GoSirk" class="opacity-25" style="width: 120px;">
                    <?php endif; ?>
                  </div>
                  <div class="card-body p-4 d-flex flex-column">
                    <h5 class="fw-bold mb-2" data-lang-id="<?= $item->title_id ?>" data-lang-en="<?= $item->title_en ?>"><?= $item->title_id ?></h5>
                    <p class="text-muted small mb-3 flex-grow-1" data-lang-id="<?= strip_tags($item->description_id) ?>" data-lang-en="<?= strip_tags($item->description_en) ?>">
                      <?= strip_tags($item->description_id) ?>
                    </p>
                    <div class="mt-auto">
                        <a href="<?= BASE_URL ?>gi/detail/<?= $item->slug ?>" class="btn btn-gi-orange rounded-pill d-inline-flex align-items-center gap-2">
                            <span data-i18n="home.common.learn_more">Pelajari lebih lanjut</span> <i class="bi bi-box-arrow-up-right small"></i>
                        </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
      </div>
    </div>

    <!-- Implementasi Partner -->
    <div class="mb-5">
      <h3 class="fw-bold mb-4" data-i18n="home.services.pd_title">Pengembangan Program dan Implementasi Partner</h3>
      <div class="row g-4 service-detail-grid">
          <?php if (!empty($services_pd)) : ?>
            <?php foreach ($services_pd as $item) : ?>
              <div class="col-md-6 col-lg-4">
                <div class="card border border-light shadow-sm h-100 rounded-3">
                  <div class="card-image-wrapper bg-light d-flex align-items-center justify-content-center rounded-top-3" style="height: 200px; overflow: hidden;">
                    <?php if (!empty($item->image)) : ?>
                      <img src="<?= ASSETS_URL ?>img/services/<?= $item->image ?>" alt="<?= $item->title_id ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                    <?php else : ?>
                      <img src="<?= ASSETS_URL ?>img/Logo-GoSirk-01.png" alt="GoSirk" class="opacity-25" style="width: 120px;">
                    <?php endif; ?>
                  </div>
                  <div class="card-body p-4 d-flex flex-column">
                    <h5 class="fw-bold mb-2" data-lang-id="<?= $item->title_id ?>" data-lang-en="<?= $item->title_en ?>"><?= $item->title_id ?></h5>
                    <p class="text-muted small mb-0" data-lang-id="<?= strip_tags($item->description_id) ?>" data-lang-en="<?= strip_tags($item->description_en) ?>">
                      <?= strip_tags($item->description_id) ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
      </div>
    </div>

    <!-- Consultancy -->
    <div class="mb-5">
      <div class="mb-4">
        <h3 class="fw-bold" data-i18n="home.services.cs_title">Layanan Konsultansi & Advisory Strategis</h3>
      </div>
      
      <div class="row g-4">
        <?php if (!empty($services_cs)) : ?>
          <?php
            $consultancyIcons = ['manage_search', 'insights', 'fact_check', 'query_stats'];
            foreach ($services_cs as $index => $item) :
          ?>
            <div class="col-md-3">
              <div class="approach-card p-4 h-100">
                <span class="material-symbols-outlined" style="font-size: 48px;">
                  <?= $consultancyIcons[$index % count($consultancyIcons)] ?>
                </span>
                <h5 data-lang-id="<?= $item->title_id ?>" data-lang-en="<?= $item->title_en ?>"><?= $item->title_id ?></h5>
                <p data-lang-id="<?= strip_tags($item->description_id) ?>" data-lang-en="<?= strip_tags($item->description_en) ?>">
                  <?= strip_tags($item->description_id) ?>
                </p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>
  
</section>

<!-- PORTFOLIO & PARTNERSHIP -->
<section id="portfolio" class="section bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold" data-i18n="home.portfolio.title">PORTOFOLIO & KEMITRAAN KAMI</h2>
      <p class="text-muted" data-i18n="home.portfolio.subtitle">Portofolio kerja sama kami mencerminkan komitmen GoSirk dalam memperluas dampak melalui kolaborasi strategis.</p>
    </div>

    <!-- Portfolios marked "show on home": grid of max 3 x 3 -->
    <div class="row g-4">
      <?php
      $defaultIcons = ['institute' => 'fas fa-folder', 'partner' => 'fas fa-handshake', 'advisory' => 'fas fa-lightbulb'];
      $homePortfolios = array_slice(array_values(array_filter($portfolios ?? [], fn($p) => $p->show_home)), 0, 9);
      foreach ($homePortfolios as $p) :
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <?php if ($p->cover_image) : ?>
              <div class="portfolio-cover" style="height: 180px; background-image: url('<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>'); background-size: cover; background-position: center;"></div>
            <?php else : ?>
              <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 180px;">
                <i class="<?= $p->icon_name ?: ($defaultIcons[$p->home_category] ?? 'fas fa-folder') ?> text-muted opacity-25" style="font-size: 80px;"></i>
              </div>
            <?php endif; ?>
            <div class="card-body p-4 d-flex flex-column">
              <h6 class="fw-bold mb-1" data-lang-id="<?= $p->title_id ?>" data-lang-en="<?= $p->title_en ?>"><?= $p->title_id ?></h6>
              <p class="text-muted small mb-3"><?= $p->client_name ?: '&nbsp;' ?></p>
              <p class="card-text small text-secondary flex-grow-1" data-lang-id="<?= $p->subtitle_id ?>" data-lang-en="<?= $p->subtitle_en ?>">
                <?= $p->subtitle_id ?>
              </p>
              <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-outline-secondary btn-sm rounded-pill align-self-end mt-3 px-3" data-i18n="home.common.read_more">Selengkapnya</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if (empty($homePortfolios)) : ?>
        <div class="col-12 text-center p-5">
          <p class="text-muted fst-italic" data-i18n="home.portfolio.empty">Belum ada portofolio tersedia saat ini.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<!-- ECOSYSTEM -->
<?php
  $ggcHeroLogo = $data['ggc_hero_logo'] ?? 'logo-ggc.png';
  $ggcHeroLogoUrl = $ggcHeroLogo && filter_var($ggcHeroLogo, FILTER_VALIDATE_URL) ? $ggcHeroLogo : ASSETS_URL . 'img/' . $ggcHeroLogo;
  $gnpHeroLogo = $data['go_ngompos_project_hero_logo'] ?? 'logo-go-ngompos.svg';
  $gnpHeroLogoUrl = $gnpHeroLogo && filter_var($gnpHeroLogo, FILTER_VALIDATE_URL) ? $gnpHeroLogo : ASSETS_URL . 'img/' . $gnpHeroLogo;
?>
<section class="section ecosystem">
  <div class="container text-center">
    <h2 class="fw-bold mb-2" data-i18n="home.ecosystem.title">Our Ecosystem</h2>
    <p class="mb-5" data-i18n="home.ecosystem.desc" data-i18n-html="true">GoSirk beroperasi melalui unit khusus dalam ekosistem bisnis GoSirk untuk memastikan riset, edukasi, <br>
    dan keterlibatan komunitas terintegrasi</p>
    <div class="row g-4 justify-content-center">

      <div class="col-md-5">
        <div class="stat-box">
          <img src="<?= ASSETS_URL ?>img/logo-gi.png" alt="GoSirk Institute" class="ecosystem-logo">
          <div class="ecosystem-content">
            <h5 data-i18n="home.ecosystem.gi.title">GoSirk Institute</h5>
            <p data-i18n="home.ecosystem.gi.desc">Platform edukasi persampahan berbasis praktik dan pengalaman lapangan</p>
            <a href="<?= BASE_URL ?>gi" class="btn btn-sm" data-i18n="home.common.read_more">
              Selengkapnya
            </a>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="stat-box">
          <img src="<?= htmlspecialchars($ggcHeroLogoUrl, ENT_QUOTES) ?>" alt="GoSirk Green Community" class="ecosystem-logo">
          <div class="ecosystem-content">
            <h5 data-i18n="home.ecosystem.ggc.title">GoSirk Green Community</h5>
            <p data-i18n="home.ecosystem.ggc.desc">Solusi nyata dalam pengelolaan sampah berbasis komunitas</p>
            <a href="<?= BASE_URL ?>ggc" class="btn btn-sm" data-i18n="home.common.read_more">
              Selengkapnya
            </a>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="stat-box">
          <img src="<?= htmlspecialchars($gnpHeroLogoUrl, ENT_QUOTES) ?>" alt="Go Ngompos Project" class="ecosystem-logo">
          <div class="ecosystem-content">
            <h5 data-i18n="home.ecosystem.gnp.title">Go Ngompos Project</h5>
            <p data-i18n="home.ecosystem.gnp.desc">Gerakan pengolahan sampah organik menjadi kompos dari rumah dan komunitas</p>
            <a href="<?= BASE_URL ?>go_ngompos_project" class="btn btn-sm" data-i18n="home.common.read_more">
              Selengkapnya
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CONTRIBUTIONS & PARTNERS: all logos -->
<section class="section bg-light">
  <div class="container">
    <h3 class="text-center fw-bold mb-5" data-i18n="home.partners.title">OUR CONTRIBUTIONS AND PARTNER</h3>
    <?php
      $contributions = array_filter($partners ?? [], fn($ptr) => ($ptr->category ?? '') == 'contribution');
      $publicPath = dirname($_SERVER['SCRIPT_FILENAME']);
    ?>
    <?php if ($contributions) : ?>
      <div class="partner-logo-grid">
        <?php foreach ($contributions as $ptr) :
          $logoFile = $ptr->logo;
          if (empty($logoFile)) {
              $logoUrl = ASSETS_URL . 'img/Logo-GoSirk-01.png';
          } elseif (!file_exists($publicPath . '/assets/img/partners/' . $logoFile) && file_exists($publicPath . '/assets/img/' . $logoFile)) {
              $logoUrl = ASSETS_URL . 'img/' . $logoFile;
          } else {
              $logoUrl = ASSETS_URL . 'img/partners/' . $logoFile;
          }
        ?>
          <div class="partner-logo-item">
            <img src="<?= $logoUrl ?>" alt="<?= htmlspecialchars($ptr->name) ?>" title="<?= htmlspecialchars($ptr->name) ?>" loading="lazy" onerror="this.src='<?= ASSETS_URL ?>img/Logo-GoSirk-01.png'; this.style.opacity='0.3';">
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="text-center text-muted small" data-i18n="home.partners.empty">No contribution partners yet</div>
    <?php endif; ?>
  </div>
</section>

<!-- BLOG SECTION -->
<section class="section blog-section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h3 class="fw-bold mb-0" data-i18n="home.blog_title">BLOG</h3>
      <div class="blog-nav">
        <a href="<?= BASE_URL ?>blog" class="btn btn-outline-primary btn-m rounded-pill" data-i18n="home.common.read_more">Selengkapnya</a>
        <button class="btn-nav prev-blog"><i class="fas fa-chevron-left"></i></button>
        <button class="btn-nav next-blog"><i class="fas fa-chevron-right"></i></button>
      </div>
    </div>
    
    <div class="swiper blog-slider">
      <div class="swiper-wrapper">
        <?php if (!empty($articles)) : ?>
          <?php foreach (array_slice($articles, 0, 6) as $art) : ?>
            <?php
              $excerptId = trim(strip_tags($art->content_id));
              $excerptEn = trim(strip_tags($art->content_en));
              $excerptLimit = 120;
              $truncatedExcerptId = strlen($excerptId) > $excerptLimit ? substr($excerptId, 0, $excerptLimit) . '...' : $excerptId;
              $truncatedExcerptEn = strlen($excerptEn) > $excerptLimit ? substr($excerptEn, 0, $excerptLimit) . '...' : $excerptEn;
            ?>
            <div class="swiper-slide">
              <div class="blog-card">
                <div class="blog-image">
                  <?php if ($art->image) : ?>
                    <img src="<?= ASSETS_URL ?>img/blog/<?= $art->image ?>" alt="<?= $art->title_id ?>">
                  <?php else : ?>
                    <div class="w-100 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"><i class="fas fa-newspaper text-muted opacity-25" style="font-size: 64px;"></i></div>
                  <?php endif; ?>
                </div>
                <div class="blog-content">
                  <h6 class="blog-title" data-lang-id="<?= $art->title_id ?>" data-lang-en="<?= $art->title_en ?>"><?= $art->title_id ?></h6>
                  <span class="blog-tag text-uppercase"><?= $art->category ?: 'ARTICLE' ?></span>
                  <div class="blog-excerpt mt-2" data-lang-id="<?= $truncatedExcerptId ?>" data-lang-en="<?= $truncatedExcerptEn ?>">
                    <?= $truncatedExcerptId ?>
                  </div>
                  <a href="<?= BASE_URL ?>blog/detail/<?= $art->id ?>" class="blog-link" data-i18n="home.common.read_more">Selengkapnya</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="swiper-slide">
            <div class="text-center p-5">
              <p class="text-muted" data-i18n="common.no_articles">Belum ada artikel.</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    
  </div>
</section>


<!-- CTA -->
<section class="section cta-section" style="background-image: linear-gradient(rgba(165, 165, 165, 0.562), rgba(255, 255, 255, 0.8)), url('<?= PageImages::attr('home.cta_bg') ?>');">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h2 class="fw-bold mb-3" data-i18n="home.cta.title">Siap Berkolaborasi Bersama Kami?</h2>
        <p class="mb-5 lead" data-i18n="home.cta.desc">Dapatkan detail profil perusahaan atau konsultasikan kebutuhan Anda.</p>
        
        <div class="d-flex flex-column align-items-center gap-3">
          <?php if ($data['company_profile']) : ?>
          <button type="button" class="btn btn-warning btn-cta-yellow rounded-pill px-4 py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#companyProfileModal">
            <span class="material-symbols-outlined">description</span>
            <span data-i18n="home.cta.button_profile">Dapatkan Company Profile</span>
          </button>
          <?php endif; ?>
          
          <a href="<?= BASE_URL ?>collaboration" class="btn btn-warning btn-cta-yellow rounded-pill px-4 py-2 d-flex align-items-center gap-2">
             <span class="material-symbols-outlined">description</span>
            <span data-i18n="home.cta.button_collab" data-i18n-html="true">We Are Calling for Collaboration. <i class="text-decoration-underline">Click Here for more information</i></span> 
          </a>

          <a href="<?= BASE_URL ?>contact" class="btn btn-outline-dark rounded-pill px-5 py-2" data-i18n="home.cta.button_contact">
            Hubungi Kami
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BANNER -->
<div class="banner-section">
    <img src="<?= PageImages::attr('home.banner') ?>" alt="Banner GoSirk" class="img-fluid w-100">
</div>

<!-- Company Profile Modal -->
<div class="modal fade" id="companyProfileModal" tabindex="-1" aria-labelledby="companyProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="companyProfileModalLabel" data-i18n="home.modal.title">Dapatkan Company Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start">
        <p class="mb-4 text-muted small" data-i18n="home.modal.desc">Silakan isi formulir di bawah ini untuk mendapatkan profil perusahaan terbaru kami.</p>
        <form id="cpDownloadForm">
          <input type="hidden" name="doc_id" value="<?= $data['company_profile']->id ?? '' ?>">
          <div class="mb-3">
            <label for="cpName" class="form-label" data-i18n="home.modal.name">Nama Lengkap</label>
            <input type="text" class="form-control" id="cpName" placeholder="Masukkan nama Anda" data-i18n="home.modal.name_placeholder" <?= FormRules::attrs('doc_request', 'name') ?>>
          </div>
          <div class="mb-3">
            <label for="cpEmail" class="form-label" data-i18n="home.modal.email">Alamat Email</label>
            <input type="email" class="form-control" id="cpEmail" placeholder="name@example.com" <?= FormRules::attrs('doc_request', 'email') ?>>
          </div>
          <div class="mb-3">
            <label for="cpOrganization" class="form-label" data-i18n="home.modal.org">Instansi / Perusahaan</label>
            <input type="text" class="form-control" id="cpOrganization" placeholder="Nama instansi Anda" data-i18n="home.modal.org_placeholder" <?= FormRules::attrs('doc_request', 'organization') ?>>
          </div>
          <div class="mb-3">
            <label for="cpJabatan" class="form-label" data-i18n="home.modal.position">Jabatan</label>
            <input type="text" class="form-control" id="cpJabatan" placeholder="Jabatan Anda" data-i18n="home.modal.position_placeholder" <?= FormRules::attrs('doc_request', 'jabatan') ?>>
          </div>
          <div class="d-grid gap-2 mt-4"> 
            <button type="submit" class="btn btn-warning rounded-pill text-white fw-bold" data-i18n="home.modal.submit">Kirim</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cpForm = document.getElementById('cpDownloadForm');
    if (cpForm) {
        cpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

            const formData = new FormData();
            formData.append('doc_id', this.querySelector('input[name="doc_id"]').value);
            formData.append('name', document.getElementById('cpName').value);
            formData.append('email', document.getElementById('cpEmail').value);
            formData.append('organization', document.getElementById('cpOrganization').value);
            formData.append('jabatan', document.getElementById('cpJabatan').value);

            fetch('<?= BASE_URL ?>collaboration/request', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    GosirkLead.saveFrom('cp');
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#29b471'
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('companyProfileModal'));
                        modal.hide();
                        cpForm.reset();
                        GosirkLead.fill('cp');
                    });
                } else {
                    Swal.fire({
                        title: 'Oops!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem.',
                    icon: 'error'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            });
        });
    }

});
</script>
