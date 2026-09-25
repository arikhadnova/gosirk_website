<?php
// "OUR IMPACT FOOTPRINT": main impact numbers (Admin > Dampak Home, section "Main") + link to the detail page.
// Used on Home and Implementasi Partner.
$footprint = array_filter($data['impacts'] ?? [], fn($imp) => $imp->section === 'Main');
?>
<section class="section bg-light impact-footprint">
  <div class="container">
    <h3 class="text-center fw-bold mb-5" data-i18n="home.impact.title">KAMI MULAI MENCIPTAKAN DAMPAK</h3>
    <div class="row g-4 justify-content-center">
      <?php foreach ($footprint as $imp) : ?>
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
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
      <a href="<?= BASE_URL ?>home/impact" class="btn btn-outline-primary rounded-pill px-4" data-i18n="home.common.read_more">Selengkapnya</a>
    </div>
  </div>
</section>

<style>
  .impact-footprint { padding: 80px 0; }
  .impact-footprint .stat-box {
    background: #fff;
    border-radius: 14px;
    padding: 24px;
    height: 100%;
    text-align: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
  }
  .impact-footprint .stat-box p { margin-bottom: 0; }
</style>
