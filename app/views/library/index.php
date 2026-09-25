<!-- LIBRARY HERO -->
<section class="section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary rounded-pill px-3 py-2 mb-3 fw-bold" data-i18n="library.badge">GOSIRK INSTITUTE</span>
            <h2 class="fw-bold display-5 mb-3" data-i18n="library.title">Library & Resources</h2>
            <p class="lead text-muted mx-auto" style="max-width: 800px;" data-i18n="library.subtitle">
                Kumpulan artikel, hasil penelitian, dan materi edukatif untuk mendukung pengelolaan sampah berkelanjutan.
            </p>
        </div>

        <!-- Library Filter Tags (Optional for better UX) -->
        <div class="d-flex justify-content-center gap-2 mb-5 flex-wrap">
            <button type="button" class="btn btn-outline-dark rounded-pill px-4 active lib-filter" data-category="all" data-i18n="library.category_all">Semua</button>
            <?php foreach (Article_model::usedCategories($articles ?? []) as $cat) : $catKey = Article_model::CATEGORIES[$cat] ?? null; ?>
                <button type="button" class="btn btn-outline-dark rounded-pill px-4 lib-filter" data-category="<?= htmlspecialchars($cat) ?>"<?= $catKey ? ' data-i18n="' . $catKey . '"' : '' ?>><?= htmlspecialchars($cat) ?></button>
            <?php endforeach; ?>
        </div>

        <div class="row g-4 mb-5">
            <?php if (!empty($articles)) : ?>
                <?php foreach ($articles as $item) : ?>
                <!-- Article Item -->
                <div class="col-lg-4 col-md-6 lib-item" data-category="<?= htmlspecialchars($item->category) ?>">
                    <div class="card h-100 article-card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="article-image" style="height: 220px; overflow: hidden;">
                            <?php if ($item->image) : ?>
                                <img src="<?= ASSETS_URL ?>img/blog/<?= $item->image ?>" class="w-100 h-100 object-fit-cover" alt="<?= $item->title_id ?>">
                            <?php else : ?>
                                <div style="height: 100%; background-color: #f3eff5; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-book text-muted opacity-25 display-4"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="article-content p-4 d-flex flex-column h-100">
                            <div class="flex-grow-1">
                                <h5 class="article-title fw-bold" data-lang-id="<?= $item->title_id ?>" data-lang-en="<?= $item->title_en ?>">
                                    <?= $item->title_id ?>
                                </h5>
                                <span class="article-tag badge bg-light text-secondary mb-3"><?= $item->category ?></span>
                                <p class="article-excerpt text-muted small" data-lang-id="<?= substr(strip_tags($item->content_id), 0, 100) ?>..." data-lang-en="<?= substr(strip_tags($item->content_en), 0, 100) ?>...">
                                    <?= substr(strip_tags($item->content_id), 0, 100) ?>...
                                </p>
                            </div>
                            <div class="mt-3 pt-3 border-top text-end">
                                <a href="<?= BASE_URL ?>blog/detail/<?= $item->id ?>" class="btn btn-link article-link p-0 text-decoration-none fw-bold" data-i18n="library.read_more">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted" data-i18n="library.empty">Belum ada resource di library saat ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<style>
    .article-card {
        transition: all 0.3s ease;
    }
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .article-link {
        color: #0d4a7c;
        font-size: 0.9rem;
    }
    .article-link:hover {
        color: #0d6efd;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.lib-filter');
    buttons.forEach((btn) => btn.addEventListener('click', () => {
        buttons.forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
        const cat = btn.dataset.category;
        document.querySelectorAll('.lib-item').forEach((item) => {
            item.style.display = cat === 'all' || item.dataset.category === cat ? '' : 'none';
        });
    }));
});
</script>
