<?php
$heroGnp = $data['hero'] ?? null;
$gnpHeroImages = json_decode($heroGnp->image ?? '', true);
$gnpHeroImages = is_array($gnpHeroImages) ? $gnpHeroImages : [$heroGnp->image ?? ''];
$gnpHeroImages = array_values(array_filter(array_map(function ($image) {
    return $image && strpos($image, 'http') !== 0 ? ASSETS_URL . 'img/' . $image : $image;
}, $gnpHeroImages)));
if (empty($gnpHeroImages)) $gnpHeroImages[] = 'https://images.unsplash.com/photo-1589923188900-85dae523342b?auto=format&fit=crop&q=80&w=900';
$heroTransition = in_array(($data['hero_transition'] ?? 'slide'), ['slide', 'fade']) ? $data['hero_transition'] : 'slide';
$gnpLogo = $data['hero_logo'] ?? 'logo-go-ngompos.svg';
$gnpLogoUrl = $gnpLogo && filter_var($gnpLogo, FILTER_VALIDATE_URL) ? $gnpLogo : ASSETS_URL . 'img/' . $gnpLogo;

$impactItems = $data['impacts'] ?? [];
?>
<section class="hero-ggc">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 d-none d-lg-block">
        <div class="hero-visual-frame">
          <div class="hero-visual-bg bg-success rounded-circle opacity-50"></div>
          <div class="hero-image-slider hero-image-slider-<?= $heroTransition ?> rounded-5 shadow-lg position-relative z-1 overflow-hidden">
            <?php foreach (array_slice($gnpHeroImages, 0, 5) as $index => $slide): ?>
              <img src="<?= htmlspecialchars($slide, ENT_QUOTES) ?>" class="hero-image-slide <?= $index === 0 ? 'active' : '' ?>" alt="Composting organic waste">
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ps-lg-5">
          <div class="hero-logo-wrapper mb-4 text-start">
            <img src="<?= htmlspecialchars($gnpLogoUrl, ENT_QUOTES) ?>?v=<?= time() ?>" alt="Go Ngompos Project" class="img-fluid" style="max-height: 180px;">
          </div>
          <p class="lead text-secondary text-start mb-4 fs-4" style="max-width: 520px;"
             data-lang-id="<?= htmlspecialchars($heroGnp->subtitle_id ?? 'Gerakan pengolahan sampah organik menjadi kompos dari rumah, sekolah, kantor, dan komunitas.') ?>"
             data-lang-en="<?= htmlspecialchars($heroGnp->subtitle_en ?? 'A movement to turn organic waste into compost from homes, schools, offices, and communities.') ?>">
            <?= $heroGnp->subtitle_id ?? 'Gerakan pengolahan sampah organik menjadi kompos dari rumah, sekolah, kantor, dan komunitas.' ?>
          </p>
          <div class="d-flex justify-content-start gap-3 flex-wrap">
            <a href="#about" class="btn btn-success btn-sm rounded-pill px-4 py-3 fw-bold shadow-sm" data-i18n="ggc.hero.cta_learn">Pelajari Lebih Lanjut</a>
            <a href="#programs" class="btn btn-outline-success btn-sm rounded-pill px-4 py-3 fw-bold" data-i18n="gnp.hero.cta_program">Program Kami</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  :root {
    --gnp-brown: #8b5e34;
    --gnp-brown-dark: #6f4322;
  }
  .hero-visual-frame { position: relative; display: grid; place-items: center; max-width: 540px; margin-inline: auto; isolation: isolate; }
  .hero-visual-bg { position: absolute; width: min(96%, 500px); aspect-ratio: 1 / 1; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; }
  .hero-image-slider { width: 100%; aspect-ratio: 4 / 3; background: #f8f9fa; }
  .hero-image-slide { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .hero-image-slider-fade .hero-image-slide { opacity: 0; transform: scale(1.03); transition: opacity 1s ease, transform 6s ease; }
  .hero-image-slider-fade .hero-image-slide.active { opacity: 1; transform: scale(1); }
  .hero-image-slider-slide .hero-image-slide { opacity: 1; transform: translateX(100%); transition: transform 0.85s ease; }
  .hero-image-slider-slide .hero-image-slide.active { transform: translateX(0); z-index: 2; }
  .hero-image-slider-slide .hero-image-slide.previous { transform: translateX(-100%); z-index: 1; }
  .hero-ggc .btn-outline-success {
    color: var(--gnp-brown);
    border-color: var(--gnp-brown);
  }
  .hero-ggc .btn-outline-success:hover {
    color: #fff;
    background-color: var(--gnp-brown);
    border-color: var(--gnp-brown);
  }
  .section-title-wrapper h4.text-success,
  #about .text-success,
  .metric-card .metric-value {
    color: var(--gnp-brown) !important;
  }
  #programs .bg-primary {
    background-color: var(--gnp-brown) !important;
  }
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

<section class="section bg-white border-top">
  <div class="container">
    <div class="section-title-wrapper text-center">
      <div class="title-bg" data-i18n="gnp.impact.title_bg">Impact</div>
      <h4 class="fw-bold fs-2 mb-2 text-success" data-i18n="gnp.impact.title">DAMPAK YANG INGIN DIBANGUN</h4>
      <p class="text-muted" data-i18n="gnp.impact.subtitle">Mendorong kebiasaan mengolah sampah organik dari sumbernya.</p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php if (!empty($impactItems)) : ?>
      <?php foreach ($impactItems as $impact) : ?>
        <div class="col-lg-3 col-md-6">
          <div class="metric-card">
            <div class="metric-value"><?= $impact->value ?></div>
            <div class="metric-unit" data-lang-id="<?= htmlspecialchars($impact->unit ?? '', ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($impact->unit_en ?? $impact->unit ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($impact->unit ?? '') ?></div>
            <div class="metric-label" data-lang-id="<?= htmlspecialchars($impact->label_id ?? '', ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($impact->label_en ?? $impact->label_id ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($impact->label_id ?? '') ?></div>
            <div class="metric-note" data-lang-id="<?= htmlspecialchars($impact->note_id ?? '', ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($impact->note_en ?? $impact->note_id ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($impact->note_id ?? '') ?></div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php else : ?>
        <div class="col-12 text-center py-4">
          <p class="text-muted mb-0">Data dampak belum tersedia.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
  $aboutSection = $data['about_section'] ?? null;
  $aboutBadgeId = $aboutSection->badge_id ?? 'TENTANG PROGRAM';
  $aboutBadgeEn = $aboutSection->badge_en ?? 'ABOUT THE PROGRAM';
  $aboutTitleId = $aboutSection->title_id ?? 'MENGENAL <span class="text-success">GO NGOMPOS PROJECT</span>';
  $aboutTitleEn = $aboutSection->title_en ?? 'GET TO KNOW <span class="text-success">GO NGOMPOS PROJECT</span>';
  $aboutContentId = $aboutSection->content_id ?? 'Go Ngompos Project adalah inisiatif GoSirk untuk mengajak masyarakat mengurangi sampah organik yang terbuang ke TPA melalui praktik pengomposan yang mudah dan dekat dengan keseharian.';
  $aboutContentEn = $aboutSection->content_en ?? 'Go Ngompos Project is a GoSirk initiative inviting people to reduce organic waste sent to landfills through simple composting practices close to daily life.';
  $aboutContent2Id = $aboutSection->content_2_id ?? 'Program ini menggabungkan edukasi, pendampingan, dan kampanye perubahan perilaku agar rumah tangga, sekolah, kantor, dan komunitas mampu mengelola sampah organiknya sendiri.';
  $aboutContent2En = $aboutSection->content_2_en ?? 'This program combines education, assistance, and behavior change campaigns so households, schools, offices, and communities can manage their own organic waste.';
  $aboutImage = $aboutSection->image ?? 'https://images.unsplash.com/photo-1591857177580-dc82b9ac4e1e?auto=format&fit=crop&q=80&w=900';
  $aboutImageUrl = $aboutImage && filter_var($aboutImage, FILTER_VALIDATE_URL) ? $aboutImage : ASSETS_URL . 'img/' . $aboutImage;
?>
<?php if (!$aboutSection || ((int) ($aboutSection->is_active ?? 1)) === 1): ?>
<section class="section" id="about">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="bg-light p-2 rounded-5 shadow-sm overflow-hidden">
          <img src="<?= htmlspecialchars($aboutImageUrl, ENT_QUOTES) ?>" class="img-fluid rounded-4 shadow" alt="Compost and plants">
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

<section class="section bg-light-subtle" id="programs">
  <div class="container">
    <div class="section-title-wrapper text-center">
      <div class="title-bg" data-i18n="gnp.programs.title_bg">Programs</div>
      <h4 class="fw-bold fs-2 mb-2 text-success" data-i18n="gnp.programs.title">PROGRAM UTAMA</h4>
      <p class="text-muted mx-auto" style="max-width: 600px;" data-i18n="gnp.programs.subtitle">Langkah praktis untuk membangun kebiasaan ngompos yang konsisten.</p>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&q=80&w=800" alt="Edukasi Ngompos">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-success text-white rounded-pill small fw-bold" data-i18n="gnp.programs.p1_badge">Edukasi</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="gnp.programs.p1_title">Kelas Ngompos</h6>
            <p class="text-muted small" data-i18n="gnp.programs.p1_desc">Sesi belajar praktik pemilahan organik dan metode kompos sederhana.</p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=800" alt="Pendampingan Kompos">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-primary text-white rounded-pill small fw-bold" data-i18n="gnp.programs.p2_badge">Pendampingan</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="gnp.programs.p2_title">Kompos Komunitas</h6>
            <p class="text-muted small" data-i18n="gnp.programs.p2_desc">Pendampingan titik kompos bersama di lingkungan warga atau institusi.</p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&q=80&w=800" alt="Pemanfaatan Kompos">
            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 text-white rounded-pill small fw-bold" style="background-color: var(--ggc-orange);" data-i18n="gnp.programs.p3_badge">Pemanfaatan</div>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-i18n="gnp.programs.p3_title">Kebun Sirkular</h6>
            <p class="text-muted small" data-i18n="gnp.programs.p3_desc">Pemanfaatan kompos untuk tanaman pangan, toga, dan ruang hijau komunitas.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-ggc text-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h2 class="fw-bold mb-4 display-5" data-i18n="gnp.cta.title">Ayo Mulai Ngompos Bersama</h2>
        <p class="mb-5 lead opacity-75" data-i18n="gnp.cta.desc">
          Ubah sisa organik menjadi dampak baik bagi lingkungan, tanaman, dan komunitas.
        </p>
        <a href="<?= BASE_URL ?>contact" class="btn btn-ggc-light btn-lg shadow-lg" data-i18n="gnp.cta.btn">
          <i class="bi bi-chat-dots-fill me-2"></i> Hubungi Kami
        </a>
      </div>
    </div>
  </div>
</section>
