<!-- HERO SECTION -->
<?php
$heroGGC = $data['hero'];
$ggcHeroImages = json_decode($heroGGC->image ?? '', true);
$ggcHeroImages = is_array($ggcHeroImages) ? $ggcHeroImages : [$heroGGC->image ?? ''];
$ggcHeroImages = array_values(array_filter(array_map(function ($image) {
    if ($image && strpos($image, 'http') !== 0) {
        return strpos($image, 'img/') === 0 ? ASSETS_URL . $image : ASSETS_URL . 'img/' . $image;
    }
    return $image;
}, $ggcHeroImages)));
if (empty($ggcHeroImages)) $ggcHeroImages[] = ASSETS_URL . 'img/petugas-baju-biru.png';
$heroTransition = in_array(($data['hero_transition'] ?? 'slide'), ['slide', 'fade']) ? $data['hero_transition'] : 'slide';
$ggcLogo = $data['hero_logo'] ?? 'logo-ggc.png';
$ggcLogoUrl = $ggcLogo && filter_var($ggcLogo, FILTER_VALIDATE_URL) ? $ggcLogo : ASSETS_URL . 'img/' . $ggcLogo;
?>
<section class="hero-ggc">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="hero-logo-wrapper mb-4">
          <img src="<?= htmlspecialchars($ggcLogoUrl, ENT_QUOTES) ?>?v=<?= time() ?>" alt="GoSirk Green Community" class="img-fluid" style="max-height: 200px;">
        </div>
        <p class="lead text-secondary mb-4 fs-4" style="max-width: 500px;" data-lang-id="<?= $heroGGC->subtitle_id ?>" data-lang-en="<?= $heroGGC->subtitle_en ?>">
          <?= $heroGGC->subtitle_id ?>
        </p>
        <div class="d-flex gap-3">
          <a href="#about" class="btn btn-success btn-sm rounded-pill px-4 py-3 fw-bold shadow-sm" data-i18n="ggc.hero.cta_learn">Pelajari Lebih Lanjut</a>
          <a href="#gallery" class="btn btn-outline-success btn-sm rounded-pill px-4 py-3 fw-bold" data-i18n="ggc.hero.cta_action">Aksi Kami</a>
        </div>
      </div>
      <div class="col-lg-6 d-none d-lg-block">
        <div class="hero-visual-frame">
            <div class="hero-visual-bg bg-success rounded-circle opacity-50"></div>
            <div class="hero-image-slider hero-image-slider-<?= $heroTransition ?> rounded-5 shadow-lg position-relative z-1 overflow-hidden">
              <?php foreach (array_slice($ggcHeroImages, 0, 5) as $index => $slide): ?>
                <img src="<?= htmlspecialchars($slide, ENT_QUOTES) ?>" class="hero-image-slide <?= $index === 0 ? 'active' : '' ?>" alt="Community Growth">
              <?php endforeach; ?>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .hero-visual-frame { position: relative; display: grid; place-items: center; max-width: 540px; margin-inline: auto; isolation: isolate; }
  .hero-visual-bg { position: absolute; width: min(96%, 500px); aspect-ratio: 1 / 1; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; }
  .hero-image-slider { width: 100%; aspect-ratio: 4 / 3; background: #f8f9fa; }
  .hero-image-slide { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .hero-image-slider-fade .hero-image-slide { opacity: 0; transform: scale(1.03); transition: opacity 1s ease, transform 6s ease; }
  .hero-image-slider-fade .hero-image-slide.active { opacity: 1; transform: scale(1); }
  .hero-image-slider-slide .hero-image-slide { opacity: 1; transform: translateX(100%); transition: transform 0.85s ease; }
  .hero-image-slider-slide .hero-image-slide.active { transform: translateX(0); z-index: 2; }
  .hero-image-slider-slide .hero-image-slide.previous { transform: translateX(-100%); z-index: 1; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.hero-image-slider').forEach(function(slider) {
    const slides = slider.querySelectorAll('.hero-image-slide');
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

<!-- IMPACT SECTION -->
<section class="section bg-white border-top">
  <div class="container">
    <div class="section-title-wrapper text-center">
      <div class="title-bg" data-i18n="ggc.impact.title_bg">Impact</div>
      <h4 class="fw-bold fs-2 mb-2 text-success" data-i18n="ggc.impact.title">DAMPAK YANG TERUKUR</h4>
      <p class="text-muted" data-i18n="ggc.impact.subtitle">Kontribusi nyata kami dalam membangun ekosistem hijau berbasis komunitas.</p>
    </div>

    <div class="row g-4">
      <?php 
      $main_impacts = array_filter($data['impacts'], fn($i) => $i->section === 'Main');
      $collapsed_impacts = array_filter($data['impacts'], fn($i) => $i->section === 'Collapse');
      ?>
      <?php if (!empty($main_impacts)) : $idx = 1; ?>
        <?php foreach($main_impacts as $imp): ?>
          <div class="col-lg-3 col-md-6">
            <div class="metric-card">
              <div class="metric-value"><?= $imp->value ?></div>
              <div class="metric-unit" data-i18n="ggc.impact.m<?= $idx ?>_unit"><?= $imp->unit ?></div>
              <div class="metric-label" data-i18n="ggc.impact.m<?= $idx ?>_label">
                <?= $imp->label_id ?>
              </div>
              <div class="metric-note" data-i18n="ggc.impact.m<?= $idx ?>_note">
                <?= $imp->note_id ?>
              </div>
            </div>
          </div>
        <?php $idx++; endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted">No primary impact data available.</div>
      <?php endif; ?>
    </div>

    <!-- More Impacts (Collapse) -->
    <div class="collapse mt-4" id="moreImpacts">
      <div class="row g-4 justify-content-center">
        <?php if (!empty($collapsed_impacts)) : $idx = 5; // Continuing from m5 ?>
          <?php foreach($collapsed_impacts as $imp): ?>
            <div class="col-lg-3 col-md-6">
              <div class="metric-card">
                <div class="metric-value"><?= $imp->value ?></div>
                <div class="metric-unit" data-i18n="ggc.impact.m<?= $idx ?>_unit"><?= $imp->unit ?></div>
                <div class="metric-label" data-i18n="ggc.impact.m<?= $idx ?>_label">
                  <?= $imp->label_id ?>
                </div>
                <div class="metric-note" data-i18n="ggc.impact.m<?= $idx ?>_note">
                  <?= $imp->note_id ?>
                </div>
              </div>
            </div>
          <?php $idx++; endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted">No additional impact data available.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Toggle Button -->
    <div class="text-center mt-5">
      <button class="btn border-1 btn-outline-success rounded-pill px-4 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#moreImpacts" id="impactToggleButton" data-i18n="ggc.impact.btn_more">
        LIHAT DATA LENGKAP
      </button>
    </div>
  </div>
</section>

<!-- ABOUT SECTION -->
<?php
$aboutSection = $data['about_section'] ?? null;
$aboutBadgeId = $aboutSection->badge_id ?? 'SIAPA KAMI?';
$aboutBadgeEn = $aboutSection->badge_en ?? 'WHO ARE WE?';
$aboutTitleId = $aboutSection->title_id ?? 'MENGENAL <span class="text-success">GOSIRK GREEN COMMUNITY</span>';
$aboutTitleEn = $aboutSection->title_en ?? 'GET TO KNOW <span class="text-success">GOSIRK GREEN COMMUNITY</span>';
$aboutContentId = $aboutSection->content_id ?? 'GoSirk Green Community adalah inisiatif unggulan yang digagas oleh PT Go Circular Solutions Indonesia (GoSirk) yang menghadirkan solusi nyata dalam pengelolaan sampah berbasis komunitas.';
$aboutContentEn = $aboutSection->content_en ?? 'GoSirk Green Community is a flagship initiative by PT Go Circular Solutions Indonesia (GoSirk) that delivers real solutions for community-based waste management.';
$aboutContent2Id = $aboutSection->content_2_id ?? 'Melalui pendekatan partisipatif, edukatif, dan kolaborasi lintas sektor, program ini mendorong transformasi sosial dan pelestarian lingkungan di tingkat desa dan kelurahan.';
$aboutContent2En = $aboutSection->content_2_en ?? 'Through participatory, educational, and cross-sector collaboration, this program encourages social transformation and environmental preservation at village and urban community levels.';
$aboutImage = $aboutSection->image ?? 'IMG_8093-crop.jpg';
$aboutImageUrl = $aboutImage && filter_var($aboutImage, FILTER_VALIDATE_URL) ? $aboutImage : ASSETS_URL . 'img/' . $aboutImage;
?>
<?php if (!$aboutSection || ((int) ($aboutSection->is_active ?? 1)) === 1): ?>
<section class="section" id="about">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="bg-light p-2 rounded-5 shadow-sm overflow-hidden">
            <img src="<?= htmlspecialchars($aboutImageUrl, ENT_QUOTES) ?>" class="img-fluid rounded-4 shadow" alt="About GGC">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ps-lg-4">
            <div class="text-success fw-bold mb-2" data-lang-id="<?= htmlspecialchars($aboutBadgeId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutBadgeEn, ENT_QUOTES) ?>"><?= $aboutBadgeId ?></div>
            <h2 class="fw-bold mb-4 display-6" data-lang-id="<?= htmlspecialchars($aboutTitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutTitleEn, ENT_QUOTES) ?>">
              <?= $aboutTitleId ?>
            </h2>
            <p class="lead text-secondary mb-4" data-lang-id="<?= htmlspecialchars($aboutContentId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContentEn, ENT_QUOTES) ?>">
              <?= $aboutContentId ?>
            </p>
            <p class="text-muted mb-4" data-lang-id="<?= htmlspecialchars($aboutContent2Id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContent2En, ENT_QUOTES) ?>">
              <?= $aboutContent2Id ?>
            </p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- PROGRAM SECTION -->
<section class="section bg-light-subtle">
  <div class="container">
    <div class="section-title-wrapper text-center">
      <div class="title-bg" data-i18n="ggc.programs.title_bg">Programs</div>
      <h4 class="fw-bold fs-2 mb-2 text-success" data-i18n="ggc.programs.title">PROGRAM BERDAMPAK KAMI</h4>
      <p class="text-muted mx-auto" style="max-width: 600px;" data-i18n="ggc.programs.subtitle">Kami merancang inisiatif yang fokus pada pemberdayaan dan perubahan perilaku masyarakat.</p>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1526951521990-620dc14c214b?auto=format&fit=crop&q=80&w=800" alt="Pelatihan Pengolahan Sampah">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-success text-white rounded-pill small fw-bold" data-i18n="ggc.programs.p1_badge">Pemberdayaan</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="ggc.programs.p1_title">Pelatihan Pengolahan Sampah</h6>
            <p class="text-muted small" data-i18n="ggc.programs.p1_desc">
              Meningkatkan kemampuan kader desa dalam pemilahan dan pengolahan sampah.
            </p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=800" alt="Pelatihan Kader (PRA)">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-primary text-white rounded-pill small fw-bold" data-i18n="ggc.programs.p2_badge">Edukasi</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="ggc.programs.p2_title">Pelatihan Kader (PRA)</h6>
            <p class="text-muted small" data-i18n="ggc.programs.p2_desc">
              Mendorong peran kader PKK dalam pengelolaan sampah rumah tangga.
            </p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&q=80&w=800" alt="Pendampingan & Edukasi">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-orange text-white rounded-pill small fw-bold" style="background-color: var(--ggc-orange);" data-i18n="ggc.programs.p3_badge">Pendampingan</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="ggc.programs.p3_title">Pendampingan & Edukasi</h6>
            <p class="text-muted small" data-i18n="ggc.programs.p3_desc">
              Praktik pengelolaan sampah berkelanjutan di desa binaan.
            </p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=800" alt="Pengurangan Emisi">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-info text-white rounded-pill small fw-bold" data-i18n="ggc.programs.p4_badge">Lingkungan</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="ggc.programs.p4_title">Pelatihan Pengolahan Sampah</h6>
            <p class="text-muted small" data-i18n="ggc.programs.p4_desc">
              Berkontribusi dalam pengurangan emisi gas rumah kaca.
            </p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=800" alt="Penanaman Bibit">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-warning text-dark rounded-pill small fw-bold" data-i18n="ggc.programs.p5_badge">Ketahanan Pangan</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="ggc.programs.p5_title">Penanaman Bibit Tanaman</h6>
            <p class="text-muted small" data-i18n="ggc.programs.p5_desc">
              Mendukung ketahanan pangan dan pemenuhan tanaman obat.
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- GALLERY SECTION -->
<section class="section pb-5" id="gallery">
  <div class="container">
            <!-- Gallery Header -->
            <div class="position-relative text-center mb-5">
                <h4 class="fw-bold fs-2 mb-2 text-success" data-i18n="ggc.gallery.title">AKSI KAMI</h4>
                <p class="text-muted" data-i18n="ggc.gallery.subtitle">Momen-momen inspiratif dalam setiap kegiatan komunitas kami.</p>
            </div>

    <div class="row g-4 overflow-hidden">
      <?php if (!empty($data['actions'])) : ?>
        <?php foreach ($data['actions'] as $item) : ?>
          <div class="col-lg-4 col-md-6">
            <div class="gallery-item shadow-sm">
              <img src="<?= (filter_var($item->image, FILTER_VALIDATE_URL)) ? $item->image : ASSETS_URL . 'img/' . $item->image ?>" alt="<?= $item->title_id ?>">
              <div class="gallery-overlay d-flex flex-column justify-content-end p-4">
                  <h5 class="text-white fw-bold mb-1" 
                      data-lang-id="<?= $item->title_id ?>" 
                      data-lang-en="<?= $item->title_en ?>">
                      <?= $item->title_id ?>
                  </h5>
                  <p class="text-white-50 small mb-0" 
                      data-lang-id="<?= $item->description_id ?>" 
                      data-lang-en="<?= $item->description_en ?>">
                      <?= $item->description_id ?>
                  </p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="col-12 text-center py-5">
            <p class="text-muted italic">Data aksi belum tersedia.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="cta-ggc text-center">
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4 display-5" data-i18n="ggc.cta.title">Mari Bangun Dampak Bersama</h2>
            <p class="mb-5 lead opacity-75" data-i18n="ggc.cta.desc">
              Kami percaya kolaborasi yang tepat dapat menciptakan perubahan lingkungan
              yang berkelanjutan. Bersama komunitas, kita bisa memberikan kontribusi lebih bagi bumi.
            </p>
            <a href="<?= BASE_URL ?>contact" class="btn btn-ggc-light btn-lg shadow-lg" data-i18n="ggc.cta.btn">
                <i class="bi bi-chat-dots-fill me-2"></i> Hubungi Kami
            </a>
        </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const impactToggle = document.getElementById('impactToggleButton');
    const impactCollapse = document.getElementById('moreImpacts');
    
    if (impactToggle && impactCollapse) {
        impactCollapse.addEventListener('show.bs.collapse', function () {
            const lang = localStorage.getItem('gosirk_language') || 'en';
            impactToggle.textContent = resources[lang].translation.ggc.impact.btn_less;
            impactToggle.setAttribute('data-i18n', 'ggc.impact.btn_less');
        });
        
        impactCollapse.addEventListener('hide.bs.collapse', function () {
            const lang = localStorage.getItem('gosirk_language') || 'en';
            impactToggle.textContent = resources[lang].translation.ggc.impact.btn_more;
            impactToggle.setAttribute('data-i18n', 'ggc.impact.btn_more');
        });
    }
});
</script>
