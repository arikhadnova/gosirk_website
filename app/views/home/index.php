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
<section class="hero-home text-center">
  <div class="hero-home-slider hero-home-slider-<?= $heroTransition ?>" aria-hidden="true">
    <?php foreach ($heroSlides as $index => $slide) : ?>
      <div class="hero-home-slide <?= $index === 0 ? 'active' : '' ?>" style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(121, 121, 121, 0.55)), url('<?= htmlspecialchars($slide, ENT_QUOTES) ?>');"></div>
    <?php endforeach; ?>
  </div>
  <div class="container">
    <h1 class="fw-bold display-5" data-lang-id="<?= $heroHome->title_id ?>" data-lang-en="<?= $heroHome->title_en ?>" data-i18n-html="true">
      <?= $heroHome->title_id ?>
    </h1>
    <p class="lead mt-3" data-lang-id="<?= $heroHome->subtitle_id ?>" data-lang-en="<?= $heroHome->subtitle_en ?>">
      <?= $heroHome->subtitle_id ?>
    </p>
    <div class="mt-5 d-flex justify-content-center gap-3 flex-wrap">
      <a href="#services" class="btn btn-light rounded-pill text-uppercase fw-semibold" style="color: #0d4a7c !important;" data-i18n="home.hero.cta_services">Lihat Layanan</a>
      <a href="#portfolio" class="btn btn-outline-light rounded-pill text-uppercase" data-i18n="home.hero.cta_portfolio">Portofolio</a>
      <a href="<?= BASE_URL ?>contact" class="btn btn-outline-light rounded-pill text-uppercase" data-i18n="home.hero.cta_contact">Hubungi Kami</a>
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

<!-- IMPACT -->
<section class="section bg-light">
  <div class="container">
    <h3 class="text-center fw-bold mb-5" data-i18n="home.impact.title">KAMI MULAI MENCIPTAKAN DAMPAK</h3>
    <div class="row g-4 justify-content-center">
                <?php 
                $main_impacts = array_filter($data['impacts'], fn($imp) => $imp->section === 'Main');
                if (!empty($main_impacts)) : 
                    foreach ($main_impacts as $imp) : 
                ?>
                    <div class="col-md-4 col-6">
                        <div class="stat-box">
                            <h1 class="fw-bold text-primary counter" 
                                data-target="<?= $imp->value ?>" 
                                <?= strpos($imp->value, '.') !== false ? 'data-decimals="2"' : '' ?>>
                                0
                            </h1>
                            <p data-lang-id="<?= $imp->label_id ?>" data-lang-en="<?= $imp->label_en ?>">
                                <?= $imp->label_id ?>
                            </p>
                        </div>
                    </div>
                <?php 
                    endforeach; 
                endif; 
                ?>
    
    <div class="text-center mt-5">
      <a href="<?= BASE_URL ?>home/impact" class="btn btn-outline-primary rounded-pill px-4" data-i18n="home.common.read_more">Selengkapnya</a>
    </div>
  </div>
</section>

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
             $lowerTitle = strtolower($s->name_id);
             if (strpos($lowerTitle, 'capacity building') !== false || strpos($lowerTitle, 'gi') !== false || strpos($lowerTitle, 'institue') !== false) {
                 $link = BASE_URL . 'gi';
             } elseif (strpos($lowerTitle, 'implementasi') !== false || strpos($lowerTitle, 'program development') !== false) {
                 $link = BASE_URL . 'implementasi_partner';
             } elseif (strpos($lowerTitle, 'konsultansi') !== false || strpos($lowerTitle, 'advisory') !== false) {
                 $link = BASE_URL . 'konsultan';
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
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" data-i18n="home.services.cb_title">Layanan Capacity Building</h3>
        <div class="service-detail-nav d-flex gap-2">
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center prev-detail-1" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-left"></i>
           </button>
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center next-detail-1" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-right"></i>
           </button>
        </div>
      </div>
      
      <div class="swiper service-detail-slider-1">
        <div class="swiper-wrapper">
          <?php if (!empty($services_cb)) : ?>
            <?php foreach ($services_cb as $item) : ?>
              <div class="swiper-slide">
                <div class="card border border-light shadow-sm h-100 rounded-3">
                  <div class="card-image-wrapper bg-light d-flex align-items-center justify-content-center rounded-top-3" style="height: 200px; overflow: hidden;">
                    <?php if (!empty($item->image)) : ?>
                      <img src="<?= ASSETS_URL ?>img/gi/<?= $item->image ?>" alt="<?= $item->title_id ?>" class="w-100 h-100 object-fit-cover">
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
    </div>

    <!-- Implementasi Partner -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" data-i18n="home.services.pd_title">Pengembangan Program dan Implementasi Partner</h3>
        <div class="service-detail-nav d-flex gap-2">
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center prev-detail-2" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-left"></i>
           </button>
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center next-detail-2" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-right"></i>
           </button>
        </div>
      </div>
      
      <div class="swiper service-detail-slider-2">
        <div class="swiper-wrapper">
          <?php if (!empty($services_pd)) : ?>
            <?php foreach ($services_pd as $item) : ?>
              <div class="swiper-slide">
                <div class="card border border-light shadow-sm h-100 rounded-3">
                  <div class="card-image-wrapper bg-light d-flex align-items-center justify-content-center rounded-top-3" style="height: 200px; overflow: hidden;">
                    <?php if (!empty($item->image)) : ?>
                      <img src="<?= ASSETS_URL ?>img/services/<?= $item->image ?>" alt="<?= $item->title_id ?>" class="w-100 h-100 object-fit-cover">
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
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Configuration for all service detail sliders
      const sliderConfig = {
        slidesPerView: 1,
        spaceBetween: 24,
        breakpoints: {
          640: { slidesPerView: 2 },
          1024: { slidesPerView: 3 }
        }
      };

      // Initialize Slider 1
      new Swiper(".service-detail-slider-1", {
        ...sliderConfig,
        navigation: {
          nextEl: ".next-detail-1",
          prevEl: ".prev-detail-1",
        },
      });

      // Initialize Slider 2
      new Swiper(".service-detail-slider-2", {
        ...sliderConfig,
        navigation: {
          nextEl: ".next-detail-2",
          prevEl: ".prev-detail-2",
        },
      });

    });
  </script>
</section>

<!-- PORTFOLIO & PARTNERSHIP -->
<section id="portfolio" class="section bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold" data-i18n="home.portfolio.title">PORTOFOLIO & KEMITRAAN KAMI</h2>
      <p class="text-muted" data-i18n="home.portfolio.subtitle">Portofolio kerja sama kami mencerminkan komitmen GoSirk dalam memperluas dampak melalui kolaborasi strategis.</p>
    </div>

    <!-- 1. Capacity Building (GoSirk Institute) -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" data-i18n="home.portfolio.cb_title">Capacity Building (GoSirk Institute)</h4>
        <div class="d-flex gap-2">
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center prev-port-1" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-left"></i>
           </button>
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center next-port-1" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-right"></i>
           </button>
        </div>
      </div>
      
      <div class="swiper portfolio-slider-1">
        <div class="swiper-wrapper">
          <?php 
          $foundInstitute = false;
          if (!empty($portfolios)) : 
            foreach ($portfolios as $p) : 
              if ($p->show_home && $p->home_category == 'institute') :
                $foundInstitute = true;
          ?>
                <div class="swiper-slide">
                  <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                    <?php if ($p->cover_image) : ?>
                      <div class="portfolio-cover" style="height: 180px; background-image: url('<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>'); background-size: cover; background-position: center;"></div>
                    <?php else : ?>
                      <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="<?= $p->icon_name ?: 'fas fa-folder' ?> text-muted opacity-25" style="font-size: 80px;"></i>
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
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!$foundInstitute) : ?>
            <div class="swiper-slide">
                <div class="text-center p-5 w-100">
                    <p class="text-muted fst-italic" data-i18n="home.portfolio.empty">Belum ada portofolio tersedia saat ini.</p>
                </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 2. Pengembangan Program dan Implementasi Partner -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" data-i18n="home.portfolio.pd_title">Pengembangan Program dan Implementasi Partner</h4>
        <div class="d-flex gap-2">
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center prev-port-2" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-left"></i>
           </button>
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center next-port-2" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-right"></i>
           </button>
        </div>
      </div>
      
      <div class="swiper portfolio-slider-2">
        <div class="swiper-wrapper">
          <?php 
          $foundPartner = false;
          if (!empty($portfolios)) : 
            foreach ($portfolios as $p) : 
              if ($p->show_home && $p->home_category == 'partner') :
                $foundPartner = true;
          ?>
                <div class="swiper-slide">
                  <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                    <?php if ($p->cover_image) : ?>
                      <div class="portfolio-cover" style="height: 180px; background-image: url('<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>'); background-size: cover; background-position: center;"></div>
                    <?php else : ?>
                      <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="<?= $p->icon_name ?: 'fas fa-handshake' ?> text-muted opacity-25" style="font-size: 80px;"></i>
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
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!$foundPartner) : ?>
            <div class="swiper-slide">
                <div class="text-center p-5 w-100">
                    <p class="text-muted fst-italic" data-i18n="home.portfolio.empty">Belum ada portofolio tersedia saat ini.</p>
                </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 3. Konsultansi & Advisory Strategis -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" data-i18n="home.portfolio.cs_title">Konsultansi & Advisory Strategis</h4>
        <div class="d-flex gap-2">
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center prev-port-3" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-left"></i>
           </button>
           <button class="btn btn-outline-dark rounded-circle p-2 d-flex align-items-center justify-content-center next-port-3" style="width: 40px; height: 40px;">
             <i class="fas fa-arrow-right"></i>
           </button>
        </div>
      </div>
      
      <div class="swiper portfolio-slider-3">
        <div class="swiper-wrapper">
          <?php 
          $foundAdvisory = false;
          if (!empty($portfolios)) : 
            foreach ($portfolios as $p) : 
              if ($p->show_home && $p->home_category == 'advisory') :
                $foundAdvisory = true;
          ?>
                <div class="swiper-slide">
                  <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                    <?php if ($p->cover_image) : ?>
                      <div class="portfolio-cover" style="height: 180px; background-image: url('<?= ASSETS_URL ?>img/portfolio/<?= $p->cover_image ?>'); background-size: cover; background-position: center;"></div>
                    <?php else : ?>
                      <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="<?= $p->icon_name ?: 'fas fa-lightbulb' ?> text-muted opacity-25" style="font-size: 80px;"></i>
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
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!$foundAdvisory) : ?>
            <div class="swiper-slide">
                <div class="text-center p-5 w-100">
                    <p class="text-muted fst-italic" data-i18n="home.portfolio.empty">Belum ada portofolio tersedia saat ini.</p>
                </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Configuration for all portfolio sliders
      const portfolioConfig = {
        slidesPerView: 1,
        spaceBetween: 24,
        breakpoints: {
          640: { slidesPerView: 2 },
          1024: { slidesPerView: 3 }
        }
      };

      // Initialize Slider 1
      new Swiper(".portfolio-slider-1", {
        ...portfolioConfig,
        navigation: {
          nextEl: ".next-port-1",
          prevEl: ".prev-port-1",
        },
      });

      // Initialize Slider 2
      new Swiper(".portfolio-slider-2", {
        ...portfolioConfig,
        navigation: {
          nextEl: ".next-port-2",
          prevEl: ".prev-port-2",
        },
      });

      // Initialize Slider 3
      new Swiper(".portfolio-slider-3", {
        ...portfolioConfig,
        navigation: {
          nextEl: ".next-port-3",
          prevEl: ".prev-port-3",
        },
      });
    });
  </script>
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

<!-- LOGO PARTNERS SLIDER -->
<section class="section bg-light pb-4">
  <div class="container">
    <h3 class="text-center fw-bold mb-5" data-i18n="home.partners.title">OUR CONTRIBUTIONS AND PARTNER</h3>
    <div class="swiper logo-slider">
      <div class="swiper-wrapper align-items-center">
        <?php 
        $foundContribution = false;
        if (!empty($partners)) : 
          foreach ($partners as $ptr) : 
            if (($ptr->category ?? '') == 'contribution') :
              $foundContribution = true;
        ?>
            <div class="swiper-slide text-center">
              <?php 
                $logoFile = $ptr->logo;
                
                // Default to the new partners folder
                $logoUrl = ASSETS_URL . 'img/partners/' . $logoFile;
                
                // Determine absolute path for file_exists check
                $publicPath = dirname($_SERVER['SCRIPT_FILENAME']);
                $pathInPartners = $publicPath . '/assets/img/partners/' . $logoFile;
                $pathInRootImg = $publicPath . '/assets/img/' . $logoFile;

                if (!empty($logoFile)) {
                    if (file_exists($pathInPartners)) {
                        $logoUrl = ASSETS_URL . 'img/partners/' . $logoFile;
                    } elseif (file_exists($pathInRootImg)) {
                        $logoUrl = ASSETS_URL . 'img/' . $logoFile;
                    }
                } else {
                    $logoUrl = ASSETS_URL . 'img/Logo-GoSirk-01.png';
                }
              ?>
              <img src="<?= $logoUrl ?>" alt="<?= $ptr->name ?>" style="max-height: 80px; width: auto;" onerror="this.src='<?= ASSETS_URL ?>img/Logo-GoSirk-01.png'; this.style.opacity='0.3';">
            </div>
        <?php 
            endif;
          endforeach; 
        endif; 
        
        if (!$foundContribution) : ?>
          <div class="swiper-slide text-center text-muted small" data-i18n="home.partners.empty">No contribution partners yet</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="section bg-light pt-4">
  <div class="container">
    <h3 class="text-center fw-bold mb-4" data-i18n="home.network.title">OUR NETWORK</h3>
    <div class="swiper network-slider">
      <div class="swiper-wrapper align-items-center">
        <?php 
        $foundNetwork = false;
        if (!empty($partners)) : 
          foreach ($partners as $ptr) : 
            if (($ptr->category ?? 'network') == 'network') :
              $foundNetwork = true;
        ?>
            <div class="swiper-slide text-center">
              <?php 
                $logoFile = $ptr->logo;
                $logoUrl = ASSETS_URL . 'img/partners/' . $logoFile;
                
                if (!file_exists('assets/img/partners/' . $logoFile) && file_exists('assets/img/' . $logoFile)) {
                    $logoUrl = ASSETS_URL . 'img/' . $logoFile;
                }
              ?>
              <img src="<?= $logoUrl ?>" alt="<?= $ptr->name ?>" style="max-height: 60px; transition: all 0.3s;">
            </div>
        <?php 
            endif;
          endforeach; 
        endif; 
        
        if (!$foundNetwork) : ?>
          <div class="swiper-slide text-center text-muted small" data-i18n="home.network.empty">No network partners yet</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- BLOG SECTION -->
<section class="section blog-section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h3 class="fw-bold mb-0" data-i18n="home.blog.title">BLOG</h3>
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
                  <img src="<?= ASSETS_URL ?>img/blog/<?= $art->image ?>" onerror="this.src='https://images.unsplash.com/photo-1552664730-d307ca884978'">
                </div>
                <div class="blog-content">
                  <h6 class="blog-title" data-lang-id="<?= $art->title_id ?>" data-lang-en="<?= $art->title_en ?>"><?= $art->title_id ?></h6>
                  <span class="blog-tag text-uppercase"><?= $art->category ?: 'ARTICLE' ?></span>
                  <div class="blog-excerpt mt-2" data-lang-id="<?= $truncatedExcerptId ?>" data-lang-en="<?= $truncatedExcerptEn ?>">
                    <?= $truncatedExcerptId ?>
                  </div>
                  <a href="<?= BASE_URL ?>blog/detail/<?= $art->id ?>" class="blog-link">Selengkapnya</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="swiper-slide">
            <div class="text-center p-5">
              <p class="text-muted">No articles yet</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    
  </div>
</section>


<!-- CTA -->
<section class="section cta-section">
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
    <img src="<?= ASSETS_URL ?>img/banner-1.png" alt="Banner GoSirk" class="img-fluid w-100">
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
            <input type="text" class="form-control" id="cpName" placeholder="Masukkan nama Anda" data-i18n="home.modal.name_placeholder" required>
          </div>
          <div class="mb-3">
            <label for="cpEmail" class="form-label" data-i18n="home.modal.email">Alamat Email</label>
            <input type="email" class="form-control" id="cpEmail" placeholder="name@example.com" required>
          </div>
          <div class="mb-3">
            <label for="cpOrganization" class="form-label" data-i18n="home.modal.org">Instansi / Perusahaan</label>
            <input type="text" class="form-control" id="cpOrganization" placeholder="Nama instansi Anda" data-i18n="home.modal.org_placeholder" required>
          </div>
          <div class="mb-3">
            <label for="cpJabatan" class="form-label" data-i18n="home.modal.position">Jabatan</label>
            <input type="text" class="form-control" id="cpJabatan" placeholder="Jabatan Anda" data-i18n="home.modal.position_placeholder" required>
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
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#29b471'
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('companyProfileModal'));
                        modal.hide();
                        cpForm.reset();
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
