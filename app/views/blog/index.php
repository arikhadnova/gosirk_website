<section class="section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3 fw-bold" data-i18n="blog.badge">OUR STORIES</span>
            <h2 class="fw-bold display-5 mb-3" data-i18n="blog.title">Berita & Artikel</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;" data-i18n="blog.subtitle">
                Insight, update program, dan cerita perubahan dari lapangan.
            </p>
        </div>

        <!-- Search & Filter Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <!-- Search Bar -->
                <div class="card border-0 shadow-sm rounded-pill overflow-hidden mb-4 search-container">
                    <div class="card-body p-1">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent ps-4">
                                <span class="material-symbols-outlined text-muted">search</span>
                            </span>
                            <input type="text" class="form-control border-0 py-3 shadow-none bg-transparent" placeholder="Cari berita atau artikel menarik..." id="articleSearch" data-i18n-placeholder="blog.search_placeholder">
                            <button class="btn btn-warning rounded-pill px-4 fw-bold me-1 my-1" data-i18n="blog.search_btn">Cari</button>
                        </div>
                    </div>
                </div>

                <!-- Category Filters -->
                <div class="article-categories d-flex flex-wrap justify-content-center gap-2">
                    <a href="#" class="btn btn-warning rounded-pill px-4 fw-bold btn-category active" data-category="all" data-i18n="blog.category_all">Semua</a>
                    <?php foreach (Article_model::usedCategories($articles ?? []) as $cat) : $catKey = Article_model::CATEGORIES[$cat] ?? null; ?>
                        <a href="#" class="btn btn-outline-secondary rounded-pill px-4 btn-category" data-category="<?= htmlspecialchars($cat) ?>"<?= $catKey ? ' data-i18n="' . $catKey . '"' : '' ?>><?= htmlspecialchars($cat) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($articles)) : 
            $hero = $articles[0];
            $items = array_slice($articles, 1);
        ?>
        <!-- Hero Article -->
        <div class="card border-0 shadow rounded-4 overflow-hidden mb-5 doc-card article-item" data-category="<?= $hero->category ?>">
            <div class="row g-0">
                <div class="col-lg-7">
                    <?php if ($hero->image) : ?>
                        <img src="<?= ASSETS_URL ?>img/blog/<?= $hero->image ?>" class="w-100 h-100 object-fit-cover" alt="<?= $hero->title_id ?>" style="min-height: 400px;">
                    <?php else : ?>
                        <div class="w-100 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="min-height: 400px;"><i class="fas fa-newspaper text-muted opacity-25" style="font-size: 96px;"></i></div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-5">
                    <div class="card-body p-5 d-flex flex-column justify-content-center h-100 bg-dark text-white">
                        <div class="mb-3">
                             <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold" data-i18n="blog.highlight_badge">HIGHLIGHT</span>
                             <span class="ms-2 text-white-50 small"><i class="far fa-calendar-alt me-1"></i> <?= date('d M Y', strtotime($hero->created_at)) ?></span>
                        </div>
                        <h2 class="card-title fw-bold mb-3 display-6" data-lang-id="<?= $hero->title_id ?>" data-lang-en="<?= $hero->title_en ?>">
                            <?= $hero->title_id ?>
                        </h2>
                        <div class="card-text text-white-50 fs-5 mb-4" data-lang-id="<?= substr(strip_tags($hero->content_id), 0, 150) ?>..." data-lang-en="<?= substr(strip_tags($hero->content_en), 0, 150) ?>...">
                            <?= substr(strip_tags($hero->content_id), 0, 150) ?>...
                        </div>
                        <a href="<?= BASE_URL ?>blog/detail/<?= $hero->id ?>" class="btn btn-warning btn-lg text-dark fw-bold align-self-start rounded-pill px-4" data-i18n="blog.read_main">
                            Baca Artikel Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4" id="articleGrid">
            <?php foreach ($items as $item) : ?>
            <!-- Blog Item -->
            <div class="col-lg-4 col-md-6 article-item" data-category="<?= $item->category ?>">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden doc-card">
                    <div class="position-relative">
                        <?php if ($item->image) : ?>
                            <img src="<?= ASSETS_URL ?>img/blog/<?= $item->image ?>" class="card-img-top object-fit-cover" alt="<?= $item->title_id ?>" style="height: 240px;">
                        <?php else : ?>
                            <div class="w-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 240px;"><i class="fas fa-newspaper text-muted opacity-25" style="font-size: 72px;"></i></div>
                        <?php endif; ?>
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fw-bold"><?= $item->category ?></span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                             <span class="material-symbols-outlined fs-6">calendar_today</span> <?= date('d M Y', strtotime($item->created_at)) ?>
                        </div>
                        <h5 class="card-title fw-bold mb-3">
                            <a href="<?= BASE_URL ?>blog/detail/<?= $item->id ?>" class="text-dark text-decoration-none stretched-link" data-lang-id="<?= $item->title_id ?>" data-lang-en="<?= $item->title_en ?>">
                                <?= $item->title_id ?>
                            </a>
                        </h5>
                        <div class="card-text text-muted flex-grow-1" data-lang-id="<?= substr(strip_tags($item->content_id), 0, 100) ?>..." data-lang-en="<?= substr(strip_tags($item->content_en), 0, 100) ?>...">
                            <?= substr(strip_tags($item->content_id), 0, 100) ?>...
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                             <span class="material-symbols-outlined text-warning">arrow_forward</span>
                             <span class="fw-bold text-warning" data-i18n="blog.read_more">Baca Selengkapnya</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
            <div class="text-center py-5">
                <h3 class="text-muted" data-i18n="blog.empty">Belum ada artikel yang dipublikasikan.</h3>
            </div>
        <?php endif; ?>
        </div>

        <?php if (!empty($articles) && count($articles) > 12) : ?>
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
        <?php endif; ?>
    </div>
</section>

<style>
    .doc-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .doc-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .search-container {
        border: 1px solid rgba(0,0,0,0.05);
        background: #fdfdfd;
        transition: all 0.3s ease;
    }
    .search-container:focus-within {
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.08) !important;
        background: #fff;
    }
    .btn-category {
        transition: all 0.3s ease;
    }
    .btn-category:hover {
        transform: translateY(-2px);
    }
    
    /* Override Bootstrap Warning to GoSirk Orange */
    .btn-warning, .bg-warning {
        background-color: #FF8F56 !important;
        border-color: #FF8F56 !important;
        color: #fff !important;
    }
    .btn-warning:hover, .btn-warning:active {
        background-color: #e5763d !important;
        border-color: #e5763d !important;
    }
    .text-warning {
        color: #FF8F56 !important;
    }
    .border-warning {
        border-color: #FF8F56 !important;
    }
    .badge.bg-warning {
        color: #fff !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('articleSearch');
    const categoryBtns = document.querySelectorAll('.btn-category');
    const articleItems = document.querySelectorAll('.article-item');

    function filterArticles() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeBtn = document.querySelector('.btn-category.active') || document.querySelector('.btn-category.btn-warning');
        const activeCategory = activeBtn ? activeBtn.getAttribute('data-category') : 'all';

        articleItems.forEach(item => {
            const title = item.querySelector('.card-title').innerText.toLowerCase();
            const textContent = item.querySelector('.card-text') ? item.querySelector('.card-text').innerText.toLowerCase() : '';
            const category = item.getAttribute('data-category');
            
            const matchesSearch = title.includes(searchTerm) || textContent.includes(searchTerm);
            const matchesCategory = activeCategory === 'all' || category === activeCategory;

            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                // Trigger reflow for animation
                item.offsetHeight; 
            } else {
                item.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterArticles);

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            categoryBtns.forEach(b => {
                b.classList.remove('active', 'btn-warning', 'fw-bold', 'text-white');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('active', 'btn-warning', 'fw-bold', 'text-white');
            filterArticles();
        });
    });
});
</script>
