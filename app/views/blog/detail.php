<section class="section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <?php if (!empty($article)) : ?>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none text-muted" data-i18n="common.breadcrumb_home">Home</a></li>
                <?php if ($article->type == 'library') : ?>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>library" class="text-decoration-none text-muted" data-i18n="blog.breadcrumb_library">GoSirk Library</a></li>
                <?php else : ?>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>blog" class="text-decoration-none text-muted" data-i18n="blog.breadcrumb_blog">Blog</a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page" data-i18n="blog.breadcrumb_detail">Detail</li>
              </ol>
            </nav>

            <!-- Article Header -->
            <div class="mb-5">
              <div class="d-flex align-items-center gap-3 mb-3">
                 <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase"><?= $article->category ?: 'Article'; ?></span>
                 <span class="text-muted small"><i class="far fa-calendar me-2"></i> <?= date('d F Y', strtotime($article->created_at)); ?></span>
              </div>
              <h1 class="fw-bold display-5 mb-4" data-lang-id="<?= htmlspecialchars($article->title_id); ?>" data-lang-en="<?= htmlspecialchars($article->title_en); ?>">
                  <?= $article->title_id; ?>
              </h1>
              
              <div class="d-flex align-items-center gap-3">
                  <img loading="lazy" decoding="async" src="https://ui-avatars.com/api/?name=<?= urlencode($article->author ?: 'Admin'); ?>&background=random" class="rounded-circle" width="48" height="48" alt="Author">
                  <div>
                      <h6 class="fw-bold mb-0"><?= $article->author ?: 'Admin GoSirk'; ?></h6>
                      <small class="text-muted" data-i18n="blog.editorial_team">Tim Redaksi</small>
                  </div>
              </div>
            </div>

            <?php if ($article->image) : ?>
            <!-- Featured Image -->
            <div class="mb-5 rounded-4 overflow-hidden shadow-sm">
                 <img loading="lazy" decoding="async" src="<?= ASSETS_URL ?>img/blog/<?= $article->image ?>" class="img-fluid w-100 object-fit-cover" style="max-height: 500px;" alt="<?= $article->title_id ?>">
            </div>
            <?php endif; ?>

            <!-- Article Content -->
            <article class="blog-content fs-5 lh-lg text-dark mb-5" data-lang-id="<?= htmlspecialchars($article->content_id) ?>" data-lang-en="<?= htmlspecialchars($article->content_en) ?>">
              <?= $article->content_id ?>
            </article>

            <!-- Share & Tags -->
            <div class="d-flex flex-wrap justify-content-between align-items-center border-top border-bottom py-4 mb-5">
                <div class="mb-3 mb-md-0">
                    <span class="fw-bold me-2" data-i18n="blog.tags_label">Tags:</span>
                    <?php 
                    if (!empty($article->tags)) : 
                        $tags = explode(',', $article->tags);
                        foreach ($tags as $tag) : 
                            $tag = trim($tag);
                            if (!empty($tag)) :
                    ?>
                        <span class="badge bg-light text-secondary text-decoration-none border me-1">#<?= $tag ?></span>
                    <?php 
                            endif;
                        endforeach; 
                    else : 
                    ?>
                        <span class="badge bg-light text-secondary text-decoration-none border me-1">#<?= str_replace(' ', '', $article->category) ?></span>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold me-2" data-i18n="blog.share_label">Share:</span>
                    <?php 
                        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $encoded_url = urlencode($current_url);
                        $encoded_title = urlencode($article->title_id);
                    ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encoded_url ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center share-btn" style="width: 36px; height: 36px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?= $encoded_url ?>&text=<?= $encoded_title ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center share-btn" style="width: 36px; height: 36px;"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $encoded_url ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center share-btn" style="width: 36px; height: 36px;"><i class="fab fa-linkedin-in"></i></a>
                    <a href="javascript:void(0)" onclick="copyLink()" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center share-btn" style="width: 36px; height: 36px;" title="Copy Link"><i class="fas fa-link"></i></a>
                </div>
            </div>
        <?php else : ?>
            <div class="text-center py-5">
                <h2 class="text-muted">Article not found.</h2>
                <a href="<?= BASE_URL ?>blog" class="btn btn-primary mt-3" data-i18n="blog.back">Back to Blog / Library</a>
            </div>
<?php endif; ?>

        <script>
        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Link disalin ke clipboard!');
            }).catch(err => {
                console.error('Gagal menyalin link: ', err);
            });
        }
        </script>

        <style>
        .share-btn {
            transition: all 0.3s ease;
        }
        .share-btn:hover {
            background-color: #FF8F56 !important;
            border-color: #FF8F56 !important;
            color: white !important;
            transform: translateY(-3px);
        }
        .blog-content p { margin-bottom: 25px; }
        .blog-content img { max-width: 100%; height: auto; border-radius: 12px; margin: 30px 0; }
        
        /* CKEditor Content Styling Patch */
        .blog-content strong, .blog-content b { font-weight: 700 !important; color: #000; }
        .blog-content em, .blog-content i { font-style: italic !important; }
        .blog-content blockquote {
            border-left: 5px solid #FF8F56;
            padding: 20px 30px;
            margin: 30px 0;
            background: #fdf2ec;
            font-style: italic;
            border-radius: 0 12px 12px 0;
            color: #555;
            line-height: 1.8;
        }
        .blog-content ul, .blog-content ol {
            padding-left: 2rem;
            margin-bottom: 1.5rem;
        }
        .blog-content ul li { list-style-type: disc; margin-bottom: 0.5rem; }
        .blog-content ol li { list-style-type: decimal; margin-bottom: 0.5rem; }
        .blog-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }
        .blog-content table td, .blog-content table th {
            border: 1px solid #dee2e6;
            padding: 12px;
        }
        </style>
      </div>
    </div>
  </div>
</section>
