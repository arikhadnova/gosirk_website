<?php
require_once dirname(dirname(__DIR__)) . '/models/Setting_model.php';
$settingModel = new Setting_model();
$settings = $settingModel->getAll();

$site_title = $settings['site_title'] ?? 'Go Circular Solutions Indonesia';
// SEO: Admin > SEO Halaman per page, else the detail item's own title, else the global site title/description
$seoPage = explode('/', trim($_GET['url'] ?? '', '/'))[0] ?: 'home';
$seoTitle = trim($settings["seo.$seoPage.title"] ?? '');
$seoDesc = trim($settings["seo.$seoPage.description"] ?? '');
$isDetail = substr_count(trim($_GET['url'] ?? '', '/'), '/') >= 1 && !empty($data['title']);
if ($isDetail) {
    $seoTitle = strip_tags($data['title']) . ' | ' . $site_title;
    $seoDesc = trim($data['meta_description'] ?? '') ?: $seoDesc;
} elseif ($seoTitle === '' && !empty($data['title'])) {
    $seoTitle = strip_tags($data['title']) . ' | ' . $site_title; // simple pages with their own title (e.g. privacy)
}
$pageTitle = $seoTitle ?: $site_title;
$pageDesc = $seoDesc ?: ($settings['site_description'] ?? 'Go Circular Solutions Indonesia - Solusi pengelolaan sampah berkelanjutan');
$canonical = rtrim(BASE_URL, '/') . '/' . ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$site_logo = $settings['site_logo'] ?? 'Logo-GoSirk-01.png';
$logo_url = (strpos($site_logo, 'http') === 0) ? $site_logo : ASSETS_URL . 'img/' . $site_logo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="form-token" content="<?= FormGuard::token() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= htmlspecialchars($site_title, ENT_QUOTES) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($logo_url, ENT_QUOTES) ?>">
    <meta name="twitter:card" content="summary">
    <link rel="icon" type="image/png" href="<?= $logo_url ?>">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars(material_symbols_url(), ENT_QUOTES) ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    $current_page = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
    $segments = explode('/', $current_page);
    $page = $segments[0];

    if ($page == 'home' || $page == '') {
        echo '<link rel="stylesheet" href="' . asset_v('css/home.css') . '">';
    } elseif ($page == 'contact') {
        echo '<link rel="stylesheet" href="' . asset_v('css/contact.css') . '">';
    } elseif ($page == 'ggc' || $page == 'go_ngompos_project') {
        echo '<link rel="stylesheet" href="' . asset_v('css/ggc.css') . '">';
    } elseif ($page == 'partnership') {
        echo '<link rel="stylesheet" href="' . asset_v('css/partnership.css') . '">';
    } elseif ($page == 'gi' || $page == 'konsultan' || $page == 'implementasi_partner' || $page == 'implentasi_partner') {
        echo '<link rel="stylesheet" href="' . asset_v('css/gi.css') . '">';
    } elseif ($page == 'about') {
        echo '<link rel="stylesheet" href="' . asset_v('css/about.css') . '">';
    } 
    // Blog and Publication pages use inline styles or default bootstrap, no specific CSS file needed.
    echo '<link rel="stylesheet" href="' . asset_v('css/hero.css') . '">';
    ?>

    <style>
        :root {
            --gosirk-green: #29b471;
            --gosirk-dark-green: #1e8552;
            --gosirk-orange: #ff9800;
            --text-gray: #555;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            color: #333;
        }

        /* Material Symbols Icons */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 48;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* --- Navbar --- */
        .navbar {
            padding: 15px 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .navbar-brand img { height: 40px; }
        .nav-link {
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.8px;
            color: #333 !important;
            text-transform: uppercase;
            margin: 0 2px;
        }
        .lang-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }

        /* Efek Hover: Berubah warna saat kursor menempel */
        .navbar-nav .nav-link:hover {
            color: #0d4a7c !important; /* Biru sesuai draf */
            transition: color 0.3s ease; /* Transisi halus */
        }

        /* Efek Active: Warna tetap biru saat berada di halaman tersebut */
        .navbar-nav .nav-link.active {
            color: #0d4a7c !important;
            font-weight: 600; /* Menebalkan teks pada halaman aktif */
        }


        /* --- Footer --- */
        .footer {
            background: linear-gradient(135deg, #014AAD 0%, #1c273fff 100%);
            color: white;
            padding: 50px 0 40px;
            position: relative;
            overflow: hidden;
        }
        .footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }
        .footer h6 { 
            font-weight: 700; 
            margin-bottom: 25px; 
            color: #fff;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .footer p, .footer a {
            font-size: 0.88rem;
            color: #ffffffff;
            text-decoration: none;
            margin-bottom: 14px;
            display: block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            line-height: 1.6;
        }
        .footer a:hover {
            color: #3b82f6 !important;
            transform: translateX(5px);
        }
        .footer-socials {
            margin-top: 20px;
            display: flex;
            gap: 12px;
        }
        .footer-socials a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            margin-right: 0;
            font-size: 1.1rem;
            color: #9ca3af !important;
            transition: all 0.4s ease;
        }
        .footer-socials a:hover {
            background: #3b82f6;
            color: #fff !important;
            border-color: #3b82f6;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
        }
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.05);
            margin-top: 60px;
            padding-top: 30px;
            font-size: 0.8rem;
            text-align: center;
            color: #6b7280;
        }

        /*banner*/
        .contact-banner-png {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff; /* Pastikan background putih jika PNG transparan */
        }

        .img-banner-png {
            width: 100%;
            height: auto;
            display: block;
            /* Menjaga kualitas gambar PNG agar tetap tajam */
            image-rendering: -webkit-optimize-contrast;
        }

        /* Penyesuaian untuk layar sangat lebar */
        @media (min-width: 1600px) {
            .img-banner-png {
                max-width: 100%; /* Gambar akan mengikuti lebar viewport 1600px Anda */
            }
        }

  
        /* Circular Pagination */
        .pagination {
            gap: 8px;
        }
        .pagination .page-item .page-link {
            width: 40px;
            height: 40px;
            border-radius: 50% !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            border: 1px solid #dee2e6;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .pagination .page-item.active .page-link {
            background-color: #007BFF !important;
            border-color: #007BFF !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }
        .pagination .page-item.disabled .page-link {
            color: #ccc;
            background-color: #f8f9fa;
        }
        .pagination .page-item .page-link:hover:not(.active):not(.disabled) {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #007BFF;
        }

    </style>
    
    <!-- Load required JS libraries for interactive components -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="<?= ASSETS_URL ?>js/about.js"></script>
    <script src="<?= asset_v('js/translations.js') ?>"></script>
    <?php $pageTexts = class_exists('Controller') && isset($this) ? $this->model('PageText_model')->getAll() : []; ?>
    <?php if ($pageTexts) : ?>
    <script>
    // Texts edited in Admin > Teks Halaman replace the defaults before the page is translated
    (function (overrides) {
        if (typeof resources === 'undefined') return;
        Object.entries(overrides).forEach(([key, v]) => {
            ['id', 'en'].forEach((lang) => {
                if (v[lang] === null || v[lang] === '' || !resources[lang]) return;
                const parts = key.split('.');
                let node = resources[lang].translation;
                parts.slice(0, -1).forEach((p) => { node = node[p] = (node[p] && typeof node[p] === 'object') ? node[p] : {}; });
                node[parts[parts.length - 1]] = v[lang];
            });
        });
    })(<?= json_encode($pageTexts, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>);
    </script>
    <?php endif; ?>
    <script src="<?= asset_v('js/lang.js') ?>"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-lg">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                <img src="<?= $logo_url ?>" alt="<?= $site_title ?> Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDrop" role="button" data-bs-toggle="dropdown" data-i18n="nav.services">
                            Our Services
                        </a>
                        <ul class="dropdown-menu border-0 shadow">
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>gi" data-i18n="nav.capacity_building">Capacity Building</a></li>
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>implementasi_partner" data-i18n="nav.program_dev">Project Development and Implementing Partners</a></li>
                             <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>konsultan" data-i18n="nav.consultancy">Consultancy</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>partnership" data-i18n="nav.partnership">Partnership</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>about" data-i18n="nav.about">About</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-i18n="nav.ecosystem">
                            Our Ecosystem
                        </a>
                        <ul class="dropdown-menu border-0 shadow">
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>gi" data-i18n="nav.gi">GoSirk Institute</a></li>
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>ggc" data-i18n="nav.ggc">GoSirk Green Community</a></li>
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>go_ngompos_project" data-i18n="nav.gnp">Go Ngompos Project</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-i18n="nav.blog_pub">
                            Blog & Publication
                        </a>
                        <ul class="dropdown-menu border-0 shadow">
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>blog" data-i18n="nav.blog">Blog</a></li>
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>publication/gosirk" data-i18n="nav.pub_gosirk">GoSirk Publication</a></li>
                            <li><a class="dropdown-item small py-2" href="<?= BASE_URL ?>publication/reference" data-i18n="nav.pub_ref">Reference Publication</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contact" data-i18n="nav.contact">Contact Us</a></li>
                    
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle lang-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <img id="current-flag" src="https://flagcdn.com/w20/gb.png" width="20" alt="EN"> 
                            <span id="current-lang" class="ms-1">EN</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                            <li><a class="dropdown-item small lang-select" href="#" data-lang="id" data-flag="id">Bahasa Indonesia</a></li>
                            <li><a class="dropdown-item small lang-select" href="#" data-lang="en" data-flag="gb">English</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
