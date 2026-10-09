<?php
// One portfolio card (Home grid and the /portfolio page). Needs $p (a portfolio row).
$defaultIcons = ['institute' => 'fas fa-folder', 'partner' => 'fas fa-handshake', 'advisory' => 'fas fa-lightbulb'];
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
              <p class="card-text small text-secondary mb-2" data-lang-id="<?= $p->subtitle_id ?>" data-lang-en="<?= $p->subtitle_en ?>">
                <?= $p->subtitle_id ?>
              </p>
              <?php $descId = trim(strip_tags((string) $p->description_id)); $descEn = trim(strip_tags((string) $p->description_en)); ?>
              <?php if ($descId !== '') : ?>
                <p class="card-text small text-muted mb-0" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;" data-lang-id="<?= htmlspecialchars($descId) ?>" data-lang-en="<?= htmlspecialchars($descEn ?: $descId) ?>"><?= htmlspecialchars($descId) ?></p>
              <?php endif; ?>
              <div class="flex-grow-1"></div>
              <a href="<?= BASE_URL ?>portfolio/detail/<?= $p->id ?>" class="btn btn-outline-secondary btn-sm rounded-pill align-self-end mt-3 px-3" data-i18n="home.common.read_more">Selengkapnya</a>
            </div>
          </div>
        </div>
