<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Admin Panel' ?> - GoSirk</title>
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

        <!-- User Profile Section in Sidebar -->
        <div class="px-4 py-3 mb-2 border-bottom">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <img src="<?= (isset($_SESSION['user_photo']) && $_SESSION['user_photo']) ? ASSETS_URL . 'img/profile/' . $_SESSION['user_photo'] : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name'] ?? 'Admin') . '&background=FF7E5F&color=fff&size=200' ?>" 
                         class="rounded-circle border" 
                         style="width: 45px; height: 45px; object-fit: cover; background: #eee;">
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px;"><?= $_SESSION['user_name'] ?? 'Admin' ?></h6>
                    <p class="mb-0 text-muted extra-small" style="font-size: 11px;"><?= ucfirst($_SESSION['user_role'] ?? 'Admin') ?></p>
                </div>
            </div>
        </div>

        <div class="list-group list-group-flush">
            <!-- Overview -->
            <div class="mt-3 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Overview</small></div>
            <a href="<?= BASE_URL; ?>admin" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <!-- General Settings -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">General Settings</small></div>
            <a href="<?= BASE_URL; ?>admin/settings" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'settings') ? 'active' : '' ?>">
                <i class="fas fa-layer-group"></i> Layouts
            </a>
            <a href="<?= BASE_URL; ?>admin/hero" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'hero') ? 'active' : '' ?>">
                <i class="fas fa-image"></i> Hero Section
            </a>
            <a href="<?= BASE_URL; ?>admin/page_sections" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'page_sections') ? 'active' : '' ?>">
                <i class="fas fa-align-left"></i> Page Sections
            </a>
            <a href="<?= BASE_URL; ?>admin/founders" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'founders') ? 'active' : '' ?>">
                <i class="fas fa-user-tie"></i> Founders
            </a>
            <a href="<?= BASE_URL; ?>admin/partnership_settings" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'partnership_settings') ? 'active' : '' ?>">
                <i class="fas fa-users-cog"></i> Partnership Settings
            </a>
            <!-- Impact Data -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Impact Data</small></div>
            <a href="<?= BASE_URL; ?>admin/impact/home" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_home') ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Home Impact
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/gi" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_gi') ? 'active' : '' ?>">
                <i class="fas fa-university"></i> GI Impact
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/ggc" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_ggc') ? 'active' : '' ?>">
                <i class="fas fa-leaf"></i> GGC Impact
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/go_ngompos_project" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_go_ngompos_project') ? 'active' : '' ?>">
                <i class="fas fa-seedling"></i> Go Ngompos Impact
            </a>
            <a href="<?= BASE_URL; ?>admin/impact/clocc" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'impact_clocc') ? 'active' : '' ?>">
                <i class="fas fa-users"></i> CLOCC Impact
            </a>

            <!-- Service Pillars -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Service</small></div>
            <a href="<?= BASE_URL; ?>admin/services_cb" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'services_cb') ? 'active' : '' ?>">
                <i class="fas fa-school"></i> Capacity Building
            </a>
            <a href="<?= BASE_URL; ?>admin/gi_videos" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'gi_videos') ? 'active' : '' ?>">
                <i class="fas fa-video"></i> Study with Gosirk
            </a>
            <a href="<?= BASE_URL; ?>admin/services_pd" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['category']) && $data['category'] == 'pd') ? 'active' : '' ?>">
                <i class="fas fa-handshake"></i> Program Development
            </a>
            <a href="<?= BASE_URL; ?>admin/services_cs" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['category']) && $data['category'] == 'cs') ? 'active' : '' ?>">
                <i class="fas fa-lightbulb"></i> Consultancy
            </a>
            <a href="<?= BASE_URL; ?>admin/services" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'services') ? 'active' : '' ?>">
                <i class="fas fa-concierge-bell"></i> Service Categories
            </a>

            <!-- Portfolio & Content -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Portfolio & Media</small></div>
            <a href="<?= BASE_URL; ?>admin/portfolio" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'portfolio') ? 'active' : '' ?>">
                <i class="fas fa-briefcase"></i> Portofolio
            </a>
            <a href="<?= BASE_URL; ?>admin/articles" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'articles') ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i> Blog Articles
            </a>
            <a href="<?= BASE_URL; ?>admin/library" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'library') ? 'active' : '' ?>">
                <i class="fas fa-book"></i> Library Resources
            </a>
            <a href="<?= BASE_URL; ?>admin/publications" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'publications') ? 'active' : '' ?>">
                <i class="fas fa-book-open"></i> Publications 
            </a>
            <a href="<?= BASE_URL; ?>admin/testimonials" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'testimonials') ? 'active' : '' ?>">
                <i class="fas fa-quote-left"></i> Testimonials
            </a>
            <a href="<?= BASE_URL; ?>admin/faqs" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'faqs') ? 'active' : '' ?>">
                <i class="fas fa-question-circle"></i> FAQs
            </a>
            <a href="<?= BASE_URL; ?>admin/ggc_actions" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'ggc_actions') ? 'active' : '' ?>">
                <i class="fas fa-running"></i> GGC Actions
            </a>
            <a href="<?= BASE_URL; ?>admin/pilot_villages" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'pilot_villages') ? 'active' : '' ?>">
                <i class="fas fa-map-marker-alt"></i> Pilot Villages
            </a>

            <!-- Collaboration -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Collaboration</small></div>
            <a href="<?= BASE_URL; ?>admin/partners" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'partners') ? 'active' : '' ?>">
                <i class="fas fa-handshake"></i> Partners list
            </a>
            <?php if ($_SESSION['user_role'] == 'admin') : ?>
            <a href="<?= BASE_URL; ?>admin/contacts" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'contacts') ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i> Contact Messages
            </a>
            <?php endif; ?>
            <a href="<?= BASE_URL; ?>admin/collaboration" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'collaboration') ? 'active' : '' ?>">
                <i class="fas fa-file-shield"></i> Documents
            </a>
            <a href="<?= BASE_URL; ?>admin/collaboration_requests" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'collaboration_requests') ? 'active' : '' ?>">
                <i class="fas fa-history"></i> Request Logs
            </a>

            <!-- System -->
            <div class="mt-4 mb-2 ps-3"><small class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">System</small></div>
            <a href="<?= BASE_URL; ?>admin/maintenance" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'maintenance') ? 'active' : '' ?>">
                <i class="fas fa-tools"></i> Maintenance Mode
            </a>
            <?php if ($_SESSION['user_role'] == 'admin') : ?>
            <a href="<?= BASE_URL; ?>admin/users" class="list-group-item list-group-item-action d-flex align-items-center <?= (isset($data['active']) && $data['active'] == 'users') ? 'active' : '' ?>">
                <i class="fas fa-users-gear"></i> User Accounts
            </a>
            <?php endif; ?>
            
            <a href="<?= BASE_URL; ?>" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center text-secondary">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
            <a href="<?= BASE_URL; ?>auth/logout" class="list-group-item list-group-item-action d-flex align-items-center text-danger">
                <i class="fas fa-sign-out-alt"></i> Sign Out
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

                <!-- Search Bar -->
                <div class="d-none d-md-flex header-search-container align-items-center me-auto">
                    <i class="fas fa-search text-muted me-2"></i>
                    <input type="text" placeholder="Search anything...">
                </div>

                <div class="ms-auto" id="navbarSupportedContent">
                    <ul class="navbar-nav flex-row align-items-center">
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
                                <a class="dropdown-item rounded-3 mb-1" href="<?= BASE_URL; ?>admin/profile"><i class="fas fa-user-edit me-2 small"></i> Edit Profile</a>
                                <a class="dropdown-item rounded-3 mb-1" href="<?= BASE_URL; ?>admin/settings"><i class="fas fa-cog me-2 small"></i> Settings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item rounded-3 text-danger" href="<?= BASE_URL; ?>auth/logout"><i class="fas fa-sign-out-alt me-2 small"></i> Sign Out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-lg-5 py-4">
