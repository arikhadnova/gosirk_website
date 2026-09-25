<?php
  $heroHome = $data['hero'];
  $heroImageData = $heroHome->image ?? 'petugas-baju-biru.png';
  $storedHeroSlides = json_decode($heroImageData, true);
  $bgHome = is_array($storedHeroSlides) ? ($storedHeroSlides[0] ?? 'petugas-baju-biru.png') : $heroImageData;
  if ($bgHome && !filter_var($bgHome, FILTER_VALIDATE_URL)) {
      $bgHome = ASSETS_URL . 'img/' . $bgHome;
  }
?>

<!-- SUB-HERO IMPACT -->
<section class="section text-white" style="background: linear-gradient(135deg, #0d4a7c 0%, #FF8F56 100%); padding: 80px 0; margin-top: 70px;">
  <div class="container text-center">
    <h1 class="fw-bold display-4" data-i18n="home.impact_detail.hero_title">
      JEJAK DAMPAK KAMI
    </h1>
    <p class="lead mt-2" data-i18n="home.impact_detail.hero_subtitle">
      Angka-angka yang merepresentasikan komitmen kami terhadap perubahan berkelanjutan.
    </p>
  </div>
</section>

<!-- IMPACT DATA 2025 -->
<section class="section bg-light">
  <div class="container">
    
    <?php
    // One list under one heading (older "Sustainability" rows are shown here too, so no data disappears)
    $social = array_filter($data['impacts'], fn($i) => in_array($i->section, ['Social', 'Sustainability'], true));
    ?>

    <div class="mb-5">
      <div class="text-center mb-5">
        <h3 class="fw-bold text-primary mb-2" data-i18n="home.impact_detail.social_title">Project Social Impact</h3>
        <div class="mx-auto rounded-pill" style="width: 60px; height: 3px; background: #FF8F56;"></div>
      </div>

      <div class="row g-4 justify-content-center">
        <?php if (!empty($social)) : ?>
          <?php foreach($social as $imp): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
              <div class="metric-card bg-white">
                  <div class="metric-value"><?= $imp->value ?></div>
                  <div class="metric-unit" data-i18n="units.<?= strtolower(str_replace([' ', '/', '(', ')'], '_', $imp->unit ?? '')) ?>"><?= $imp->unit ?></div>
                  <div class="metric-label" data-lang-id="<?= $imp->label_id ?>" data-lang-en="<?= $imp->label_en ?>"><?= $imp->label_id ?></div>
                  <div class="metric-note" data-lang-id="<?= $imp->note_id ?>" data-lang-en="<?= $imp->note_en ?>"><?= $imp->note_id ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted" data-i18n="home.impact_detail.no_data_social">No social impact data available.</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="text-center mt-5 pt-4">
        <a href="<?= BASE_URL ?>" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm" style="background-color: #0d4a7c; border: none;" data-i18n="home.impact_detail.back_to_home">
          <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
        </a>
    </div>
  </div>
</section>

<style>
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
        box-shadow: 0 10px 25px rgba(13, 74, 124, 0.1);
        border-color: #0d4a7c;
    }
    .metric-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0d4a7c;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .metric-unit {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
    }
    .metric-label {
        font-size: 0.9rem;
        color: #0b1120;
        font-weight: 700;
        line-height: 1.4;
        margin-top: 0.5rem;
    }
    .metric-note {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.5;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px dashed #eee;
        font-style: italic;
    }
</style>

<style>
.impact-card-stat {
    background: #fff;
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05) !important;
}
.impact-card-stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}
.icon-box {
    width: 70px;
    height: 70px;
    min-width: 70px;
    min-height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(13, 74, 124, 0.1);
    color: #0d4a7c;
    margin-bottom: 1.5rem;
}
.icon-box span { font-size: 36px; }

.impact-metric-value {
    font-size: 2.75rem;
    line-height: 1;
    letter-spacing: -1px;
}

.bg-green-soft { background: rgba(41, 180, 113, 0.1); }
.text-green { color: #29b471; }
.bg-blue-soft { background: rgba(13, 110, 253, 0.1); }
.text-blue { color: #0d6efd; }
.bg-orange-soft { background: rgba(255, 193, 7, 0.1); }
.text-orange { color: #ffc107; }

@media (max-width: 768px) {
    .section { padding: 50px 0; }
    .impact-metric-value { font-size: 2.25rem; }
}
</style>
