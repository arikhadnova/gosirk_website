<section class="section error-404">
    <div class="container text-center">
        <div class="error-404-code">404</div>
        <h1 class="fw-bold mb-3" data-i18n="error404.title">Halaman tidak ditemukan</h1>
        <p class="text-muted mx-auto mb-4" style="max-width: 560px;" data-i18n="error404.desc">Maaf, halaman yang Anda cari tidak ada atau sudah dipindahkan. Periksa kembali alamatnya, atau lanjutkan ke salah satu halaman berikut.</p>
        <a href="<?= BASE_URL ?>" class="btn btn-primary rounded-pill px-4 py-2 mb-5"><i class="fas fa-home me-2"></i><span data-i18n="error404.btn_home">Kembali ke Beranda</span></a>

        <div class="error-404-links mx-auto">
            <a href="<?= BASE_URL ?>gi" data-i18n="nav.gi">GoSirk Institute</a>
            <a href="<?= BASE_URL ?>implementasi_partner" data-i18n="error404.link_partner">Implementasi Partner</a>
            <a href="<?= BASE_URL ?>konsultan" data-i18n="error404.link_consult">Konsultansi</a>
            <a href="<?= BASE_URL ?>blog" data-i18n="error404.link_blog">Blog</a>
            <a href="<?= BASE_URL ?>contact" data-i18n="error404.link_contact">Hubungi Kami</a>
        </div>
    </div>
</section>
<style>
    .error-404 { padding: 150px 0 110px; background: #f7f9fc; }
    .error-404-code {
        font-size: clamp(5rem, 16vw, 8rem); font-weight: 800; line-height: 1;
        background: linear-gradient(135deg, #0d4a7c, #FF8F56); -webkit-background-clip: text; background-clip: text; color: transparent;
        margin-bottom: 1rem;
    }
    .error-404-links { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; max-width: 640px; }
    .error-404-links a {
        padding: .5rem 1rem; border-radius: 999px; background: #fff; color: #1f2937;
        text-decoration: none; font-size: .9rem; box-shadow: 0 1px 3px rgba(16, 24, 40, .08);
    }
    .error-404-links a:hover { color: #FF8F56; }
</style>
