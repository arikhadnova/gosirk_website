<!-- All portfolios (the ones marked "show on home"), in the order set for Home -->
<section class="bg-light py-5">
  <div class="container py-lg-4">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none text-muted" data-i18n="breadcrumb.home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page" data-i18n="breadcrumb.portfolio">Portfolio</li>
      </ol>
    </nav>

    <div class="text-center mb-5">
      <h1 class="fw-bold h2" data-i18n="home.portfolio.title">PORTOFOLIO & KEMITRAAN KAMI</h1>
      <p class="text-muted" data-i18n="home.portfolio.subtitle">Portofolio kerja sama kami mencerminkan komitmen GoSirk dalam memperluas dampak melalui kolaborasi strategis.</p>
    </div>

    <div class="row g-4">
      <?php foreach ($portfolios as $p) : ?>
        <?php require dirname(__DIR__) . '/partials/portfolio_card.php'; ?>
      <?php endforeach; ?>

      <?php if (empty($portfolios)) : ?>
        <div class="col-12 text-center p-5">
          <p class="text-muted fst-italic" data-i18n="home.portfolio.empty">Belum ada portofolio tersedia saat ini.</p>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($pages > 1) : ?>
      <?php $pageUrl = fn($n) => BASE_URL . 'portfolio' . ($n > 1 ? '?page=' . $n : ''); ?>
      <nav class="mt-5" aria-label="Halaman portofolio">
        <ul class="pagination justify-content-center flex-wrap">
          <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
            <a class="page-link" href="<?= $pageUrl($page - 1) ?>" aria-label="Sebelumnya">&laquo;</a>
          </li>
          <?php for ($n = 1; $n <= $pages; $n++) : ?>
            <li class="page-item<?= $n === $page ? ' active' : '' ?>"<?= $n === $page ? ' aria-current="page"' : '' ?>>
              <a class="page-link" href="<?= $pageUrl($n) ?>"><?= $n ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item<?= $page >= $pages ? ' disabled' : '' ?>">
            <a class="page-link" href="<?= $pageUrl($page + 1) ?>" aria-label="Berikutnya">&raquo;</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</section>
