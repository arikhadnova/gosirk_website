<?php
// Program cards (Admin > <page> > Program). Used by the Go Ngompos Project and GoSirk Green Community pages.
// $data: programs, section (page_sections row "programs"), model (class name), i18n (key prefix), default_title, default_subtitle
$programsSection = $data['section'] ?? null;
$programs = $data['programs'] ?? [];
$model = $data['model'];
$i18n = $data['i18n'];
if ((!$programsSection || (int) $programsSection->is_active === 1) && $programs) :
    // Admin-defined title/subtitle, otherwise the built-in bilingual defaults
    $headingAttrs = function ($id, $en, $i18nKey) {
        return trim((string) $id) !== ''
            ? 'data-lang-id="' . htmlspecialchars($id, ENT_QUOTES) . '" data-lang-en="' . htmlspecialchars($en ?: $id, ENT_QUOTES) . '"'
            : 'data-i18n="' . $i18nKey . '"';
    };
?>
<section class="section bg-light-subtle" id="programs">
  <div class="container">
    <div class="section-title-wrapper text-center">
      <div class="title-bg" data-i18n="<?= $i18n ?>.title_bg">Programs</div>
      <h4 class="fw-bold fs-2 mb-2 text-success" <?= $headingAttrs($programsSection->title_id ?? '', $programsSection->title_en ?? '', $i18n . '.title') ?>><?= htmlspecialchars(($programsSection->title_id ?? '') ?: $data['default_title']) ?></h4>
      <p class="text-muted mx-auto" style="max-width: 600px;" <?= $headingAttrs($programsSection->content_id ?? '', $programsSection->content_en ?? '', $i18n . '.subtitle') ?>><?= htmlspecialchars(($programsSection->content_id ?? '') ?: $data['default_subtitle']) ?></p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php foreach ($programs as $p) : [, $badgeStyle] = GnpProgram_model::BADGE_COLORS[$p->badge_color] ?? GnpProgram_model::BADGE_COLORS['success']; ?>
      <div class="col-lg-4 col-md-6">
        <div class="program-card">
          <div class="program-card-img-wrapper">
            <?php if ($p->image) : ?>
              <img loading="lazy" decoding="async" src="<?= htmlspecialchars($model::imageUrl($p->image)) ?>" alt="<?= htmlspecialchars($p->title_id) ?>">
            <?php endif; ?>
            <?php if ($p->badge_id) : ?>
              <div class="position-absolute top-0 start-0 m-3 px-3 py-1 <?= $p->badge_color === 'warning' ? 'text-dark' : 'text-white' ?> rounded-pill small fw-bold" style="<?= $badgeStyle ?>"
                   data-lang-id="<?= htmlspecialchars($p->badge_id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($p->badge_en ?: $p->badge_id, ENT_QUOTES) ?>"><?= htmlspecialchars($p->badge_id) ?></div>
            <?php endif; ?>
          </div>
          <div class="program-card-content text-center">
            <h6 class="fw-bold" data-lang-id="<?= htmlspecialchars($p->title_id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($p->title_en ?: $p->title_id, ENT_QUOTES) ?>"><?= htmlspecialchars($p->title_id) ?></h6>
            <p class="text-muted small" data-lang-id="<?= htmlspecialchars($p->description_id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($p->description_en ?: $p->description_id, ENT_QUOTES) ?>"><?= htmlspecialchars($p->description_id) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
