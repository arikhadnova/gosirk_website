<?php ob_start(['Csrf', 'injectIntoForms']); // adds the CSRF token to every POST form on admin pages ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= Csrf::token() ?>">
    <script>
        // Send the CSRF token with every same-site fetch() that changes data
        (function () {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const nativeFetch = window.fetch.bind(window);
            window.fetch = function (input, init) {
                init = init || {};
                const method = (init.method || (input instanceof Request ? input.method : 'GET')).toUpperCase();
                const url = new URL(input instanceof Request ? input.url : input, location.href);
                if (method !== 'GET' && method !== 'HEAD' && url.origin === location.origin) {
                    const headers = new Headers(init.headers || (input instanceof Request ? input.headers : undefined));
                    headers.set('X-CSRF-Token', token);
                    init = Object.assign({}, init, { headers });
                }
                return nativeFetch(input, init);
            };
        })();
    </script>
    <?php $alerts = method_exists($this, 'adminAlerts') ? $this->adminAlerts() : ['requests' => 0, 'messages' => 0, 'latest_requests' => []]; $alertTotal = $alerts['requests'] + $alerts['messages']; ?>
    <title><?= $alertTotal ? '(' . $alertTotal . ') ' : '' ?><?= $data['title'] ?? 'Admin Panel' ?> - GoSirk</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/admin.css?v=<?= time() ?>">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL; ?>assets/img/Logo-GoSirk-01.png">
</head>
<body>

<div id="sidebar-overlay" class="d-none"></div>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="bg-white">
        <div class="sidebar-heading d-flex align-items-center justify-content-center position-relative">
             <img src="<?= BASE_URL; ?>assets/img/Logo-GoSirk-01.png" alt="GoSirk" style="max-height: 40px;">
             <button class="btn d-md-none border-0 p-0 text-muted position-absolute" id="sidebar-close" style="right: 1.5rem;">
                <i class="fas fa-times fs-4"></i>
             </button>
        </div>

        <div class="list-group list-group-flush">
            <!-- Overview -->
            <div class="mt-3 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Ringkasan</small></div>
            <a href="<?= BASE_URL; ?>admin" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <!-- General Settings -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Pengaturan Umum</small></div>
            <a href="<?= BASE_URL; ?>admin/settings" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'settings') ? 'active' : '' ?>">
                <i class="fas fa-layer-group"></i> Pengaturan Layout
            </a>
            <a href="<?= BASE_URL; ?>admin/hero" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'hero') ? 'active' : '' ?>">
                <i class="fas fa-image"></i> Banner Utama
            </a>
            <a href="<?= BASE_URL; ?>admin/page_sections" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'page_sections') ? 'active' : '' ?>">
                <i class="fas fa-align-left"></i> Section Halaman
            </a>
            <a href="<?= BASE_URL; ?>admin/page_texts" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'page_texts') ? 'active' : '' ?>">
                <i class="fas fa-font"></i> Teks Halaman
            </a>
            <a href="<?= BASE_URL; ?>admin/page_images" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'page_images') ? 'active' : '' ?>">
                <i class="fas fa-images"></i> Gambar Halaman
            </a>
            <a href="<?= BASE_URL; ?>admin/seo" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'seo') ? 'active' : '' ?>">
                <i class="fas fa-search"></i> SEO Halaman
            </a>
            <a href="<?= BASE_URL; ?>admin/founders" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'founders') ? 'active' : '' ?>">
                <i class="fas fa-user-tie"></i> Founder
            </a>
            <a href="<?= BASE_URL; ?>admin/partnership_settings" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'partnership_settings') ? 'active' : '' ?>">
                <i class="fas fa-users-cog"></i> Pengaturan Partnership
            </a>
            <!-- Impact Data -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Data Dampak</small></div>
            <a href="<?= BASE_URL; ?>admin/impact/home" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_home') ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dampak Home
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/gi" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_gi') ? 'active' : '' ?>">
                <i class="fas fa-university"></i> Dampak GI
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/ggc" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_ggc') ? 'active' : '' ?>">
                <i class="fas fa-leaf"></i> Dampak GGC
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/go_ngompos_project" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_go_ngompos_project') ? 'active' : '' ?>">
                <i class="fas fa-seedling"></i> Dampak Go Ngompos
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/clocc" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_clocc') ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Dampak CLOCC
            </a>

            <!-- Service Pillars -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Layanan</small></div>
            <a href="<?= BASE_URL; ?>admin/services_cb" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'services_cb') ? 'active' : '' ?>">
                <i class="fas fa-school"></i> Capacity Building
            </a>
            <a href="<?= BASE_URL; ?>admin/gi_videos" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'gi_videos') ? 'active' : '' ?>">
                <i class="fas fa-video"></i> Video Belajar Bersama
            </a>
            <a href="<?= BASE_URL; ?>admin/services_pd" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['category']) && $data['category'] == 'pd') ? 'active' : '' ?>">
                <i class="fas fa-handshake"></i> Program Development
            </a>
            <a href="<?= BASE_URL; ?>admin/services_cs" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['category']) && $data['category'] == 'cs') ? 'active' : '' ?>">
                <i class="fas fa-lightbulb"></i> Konsultansi
            </a>
            <a href="<?= BASE_URL; ?>admin/services" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'services') ? 'active' : '' ?>">
                <i class="fas fa-concierge-bell"></i> Kategori Layanan
            </a>

            <!-- Portfolio & Content -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Portofolio & Media</small></div>
            <a href="<?= BASE_URL; ?>admin/portfolio" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'portfolio') ? 'active' : '' ?>">
                <i class="fas fa-briefcase"></i> Portofolio
            </a>
            <a href="<?= BASE_URL; ?>admin/articles" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'articles') ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i> Artikel Blog
            </a>
            <a href="<?= BASE_URL; ?>admin/library" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'library') ? 'active' : '' ?>">
                <i class="fas fa-book"></i> Library
            </a>
            <a href="<?= BASE_URL; ?>admin/publications" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'publications') ? 'active' : '' ?>">
                <i class="fas fa-book-open"></i> Publikasi
            </a>
            <a href="<?= BASE_URL; ?>admin/testimonials" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'testimonials') ? 'active' : '' ?>">
                <i class="fas fa-quote-left"></i> Testimoni
            </a>
            <a href="<?= BASE_URL; ?>admin/faqs" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'faqs') ? 'active' : '' ?>">
                <i class="fas fa-question-circle"></i> FAQ
            </a>
            <a href="<?= BASE_URL; ?>admin/ggc_actions" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'ggc_actions') ? 'active' : '' ?>">
                <i class="fas fa-running"></i> Aksi GGC
            </a>
            <a href="<?= BASE_URL; ?>admin/gnp_programs" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'gnp_programs') ? 'active' : '' ?>">
                <i class="fas fa-seedling"></i> Program Go Ngompos
            </a>
            <a href="<?= BASE_URL; ?>admin/pilot_villages" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'pilot_villages') ? 'active' : '' ?>">
                <i class="fas fa-map-marker-alt"></i> Desa Pilot
            </a>
            <a href="<?= BASE_URL; ?>admin/partner_highlights" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'partner_highlights') ? 'active' : '' ?>">
                <i class="fas fa-images"></i> Sorotan Implementasi
            </a>

            <!-- Collaboration -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Kolaborasi</small></div>
            <a href="<?= BASE_URL; ?>admin/partners" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'partners') ? 'active' : '' ?>">
                <i class="fas fa-handshake"></i> Daftar Partner
            </a>
            <?php if ($_SESSION['user_role'] == 'admin') : ?>
            <a href="<?= BASE_URL; ?>admin/contacts" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'contacts') ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i> Pesan Kontak<?php if ($alerts['messages']) : ?><span class="badge rounded-pill bg-primary  ms-auto" title="Pesan belum dibaca"><?= $alerts['messages'] ?></span><?php endif; ?>
            </a>
            <?php endif; ?>
            <a href="<?= BASE_URL; ?>admin/collaboration" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'collaboration') ? 'active' : '' ?>">
                <i class="fas fa-file-shield"></i> Dokumen
            </a>
            <a href="<?= BASE_URL; ?>admin/collaboration_requests" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'collaboration_requests') ? 'active' : '' ?>">
                <i class="fas fa-history"></i> Permintaan Dokumen<?php if ($alerts['requests']) : ?><span class="badge rounded-pill bg-warning text-dark ms-auto" title="Perlu tindak lanjut"><?= $alerts['requests'] ?></span><?php endif; ?>
            </a>

            <!-- System -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Sistem</small></div>
            <a href="<?= BASE_URL; ?>admin/email_settings" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'email_settings') ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i> Pengaturan Email
            </a>
            <a href="<?= BASE_URL; ?>admin/maintenance" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'maintenance') ? 'active' : '' ?>">
                <i class="fas fa-tools"></i> Mode Pemeliharaan
            </a>
            <?php if ($_SESSION['user_role'] == 'admin') : ?>
            <a href="<?= BASE_URL; ?>admin/users" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'users') ? 'active' : '' ?>">
                <i class="fas fa-users-gear"></i> Akun Pengguna
            </a>
            <?php endif; ?>
            
            <a href="<?= BASE_URL; ?>" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center text-secondary">
                <i class="fas fa-external-link-alt"></i> Lihat Website
            </a>
            <a href="<?= BASE_URL; ?>auth/logout" class="list-group-item list-group-item-action d-flex align-items-center text-danger">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4">
            <div class="container-fluid">
                <button class="btn border-0 p-0 me-3" id="menu-toggle">
                    <i class="fas fa-bars-staggered fs-5"></i>
                </button>

                <!-- Current page title -->
                <div class="admin-topbar-title me-auto text-truncate">
                    <?= htmlspecialchars($data['title'] ?? 'Admin Panel') ?>
                </div>

                <div class="ms-auto" id="navbarSupportedContent">
                    <ul class="navbar-nav flex-row align-items-center">
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link p-0 position-relative d-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;" title="Notifikasi">
                                <i class="fas fa-bell text-secondary"></i>
                                <?php if ($alertTotal) : ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;"><?= $alertTotal ?></span><?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2" aria-labelledby="alertsDropdown" style="border-radius: 12px; width: 320px;">
                                <div class="px-3 py-2 border-bottom mb-1 fw-bold small">Perlu Perhatian</div>
                                <?php if (!$alertTotal) : ?>
                                    <div class="px-3 py-3 text-muted small text-center"><i class="fas fa-check-circle text-success me-1"></i> Tidak ada yang perlu ditindaklanjuti.</div>
                                <?php endif; ?>
                                <?php foreach ($alerts['latest_requests'] as $r) : ?>
                                    <a class="dropdown-item rounded-3 py-2 small text-wrap" href="<?= BASE_URL; ?>admin/collaboration_requests?status=followup">
                                        <i class="fas fa-file-signature text-warning me-2"></i><b><?= htmlspecialchars($r->name) ?></b> meminta <?= htmlspecialchars($r->doc_title ?: 'dokumen') ?>
                                        <div class="text-muted" style="font-size: 11px; margin-left: 22px;"><?= $r->delivery_status === 'failed' ? 'Email otomatis gagal' : 'Menunggu dikirim manual' ?> &middot; <?= date('d M H:i', strtotime($r->requested_at)) ?></div>
                                    </a>
                                <?php endforeach; ?>
                                <?php if ($alerts['requests'] > count($alerts['latest_requests'])) : ?>
                                    <a class="dropdown-item rounded-3 small text-primary" href="<?= BASE_URL; ?>admin/collaboration_requests?status=followup">Lihat semua <?= $alerts['requests'] ?> permintaan &rarr;</a>
                                <?php endif; ?>
                                <?php if ($alerts['messages']) : ?>
                                    <a class="dropdown-item rounded-3 py-2 small" href="<?= BASE_URL; ?>admin/contacts"><i class="fas fa-envelope text-primary me-2"></i><b><?= $alerts['messages'] ?></b> pesan kontak belum dibaca</a>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 text-end d-none d-sm-block">
                                        <p class="mb-0 fw-bold small text-dark"><?= $_SESSION['user_name'] ?? 'Admin' ?></p>
                                        <p class="mb-0 text-muted extra-small" style="font-size: 10px;"><?= $_SESSION['user_role'] ?? 'Super Admin' ?></p>
                                    </div>
                                    <img src="<?= (isset($_SESSION['user_photo']) && $_SESSION['user_photo']) ? ASSETS_URL . 'img/profile/' . $_SESSION['user_photo'] . '?v=' . time() : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name'] ?? 'Admin') . '&background=FF7E5F&color=fff&size=200' ?>" 
                                         class="rounded-circle border nav-profile-img" 
                                         style="width: 38px; height: 38px; object-fit: cover; background: #eee;">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2" aria-labelledby="navbarDropdown" style="border-radius: 12px; min-width: 200px;">
                                <div class="px-3 py-2 border-bottom mb-2">
                                    <p class="mb-0 fw-bold"><?= $_SESSION['user_name'] ?? 'Administrator' ?></p>
                                    <p class="mb-0 text-muted" style="font-size: 11px;"><?= $_SESSION['user_role'] ?? 'Super Admin' ?></p>
                                </div>
                                <a class="dropdown-item rounded-3 mb-1" href="<?= BASE_URL; ?>admin/profile"><i class="fas fa-user-edit me-2 small"></i> Edit Profil</a>
                                <a class="dropdown-item rounded-3 mb-1" href="<?= BASE_URL; ?>admin/settings"><i class="fas fa-cog me-2 small"></i> Pengaturan</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item rounded-3 text-danger" href="<?= BASE_URL; ?>auth/logout"><i class="fas fa-sign-out-alt me-2 small"></i> Keluar</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-lg-5 py-4">
