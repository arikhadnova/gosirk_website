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
    <link rel="stylesheet" href="<?= asset_v('css/admin.css') ?>">
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
             <img loading="lazy" decoding="async" src="<?= BASE_URL; ?>assets/img/Logo-GoSirk-01.png" alt="GoSirk" style="max-height: 40px;">
             <button class="btn d-md-none border-0 p-0 text-muted position-absolute" id="sidebar-close" style="right: 1.5rem;">
                <i class="fas fa-times fs-4"></i>
             </button>
        </div>

        <?php
        // Sidebar, page tabs and Ctrl+K search all come from AdminNav
        $navCtx = AdminNav::context($data ?? []);
        $navActive = $data['active'] ?? '';
        $isAdminRole = ($_SESSION['user_role'] ?? '') === 'admin';
        $navBadges = ['messages' => $alerts['messages'], 'requests' => $alerts['requests']];
        ?>
        <button type="button" class="admin-search-trigger" id="adminSearchOpen" title="Cari menu (Ctrl+K)">
            <i class="fas fa-search"></i><span>Cari menu…</span><kbd>Ctrl K</kbd>
        </button>

        <div class="list-group list-group-flush">
            <a href="<?= BASE_URL; ?>admin" class="list-group-item list-group-item-action d-flex align-items-center <?= $navActive === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <?php foreach (AdminNav::GROUPS as $groupKey => $groupLabel) : ?>
                <div class="sidebar-group-label"><?= $groupLabel ?></div>
                <?php foreach (AdminNav::hubs() as $hubKey => $hub) : if ($hub['group'] !== $groupKey) continue; ?>
                    <a href="<?= BASE_URL . $hub['tabs'][0]['url'] ?>" class="list-group-item list-group-item-action d-flex align-items-center <?= ($navCtx['key'] ?? '') === $hubKey ? 'active' : '' ?>">
                        <i class="fas <?= $hub['icon'] ?>"></i> <?= htmlspecialchars($hub['label']) ?>
                    </a>
                <?php endforeach; ?>
                <?php foreach (AdminNav::items()[$groupKey] ?? [] as [$key, $label, $icon, $url, $actives, $badge, $adminOnly]) : if ($adminOnly && !$isAdminRole) continue; ?>
                    <a href="<?= BASE_URL . $url ?>" class="list-group-item list-group-item-action d-flex align-items-center <?= in_array($navActive, $actives, true) ? 'active' : '' ?>">
                        <i class="fas <?= $icon ?>"></i> <?= $label ?>
                        <?php if ($badge && $navBadges[$badge]) : ?><span class="badge rounded-pill <?= $badge === 'requests' ? 'bg-warning text-dark' : 'bg-primary' ?> ms-auto"><?= $navBadges[$badge] ?></span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <div class="sidebar-group-label"></div>
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
                                    <img loading="lazy" decoding="async" src="<?= (isset($_SESSION['user_photo']) && $_SESSION['user_photo']) ? asset_v('img/profile/' . $_SESSION['user_photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name'] ?? 'Admin') . '&background=FF7E5F&color=fff&size=200' ?>" 
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

        <div class="container-fluid px-lg-5 py-4 <?= $navCtx ? 'in-hub' : '' ?>">
        <?php if ($navCtx) : $hub = $navCtx['hub']; ?>
            <!-- Page hub: every editor of this page as tabs -->
            <?php $hubFacts = AdminNav::status($hub); $hubUrl = preg_replace('#^https?://#', '', rtrim(BASE_URL, '/')) . '/' . $hub['public']; ?>
            <div class="hub-header">
                <div class="hub-head">
                    <div class="hub-icon"><i class="fas <?= $hub['icon'] ?>"></i></div>
                    <div class="hub-name">
                        <div class="hub-group"><?= AdminNav::GROUPS[$hub['group']] ?></div>
                        <h1 class="hub-title"><?= htmlspecialchars($hub['label']) ?></h1>
                        <a href="<?= BASE_URL . $hub['public'] ?>" target="_blank" class="hub-url"><?= htmlspecialchars(rtrim($hubUrl, '/')) ?> <i class="fas fa-arrow-up-right-from-square"></i></a>
                    </div>
                    <?php if ($hubFacts) : ?>
                        <div class="hub-facts">
                            <?php foreach ($hubFacts as $f) : ?>
                                <a href="<?= BASE_URL . $f['url'] ?>" class="hub-fact">
                                    <span><?= $f['label'] ?></span>
                                    <strong class="<?= $f['ok'] === true ? 'is-ok' : ($f['ok'] === false ? 'is-muted' : '') ?>"><?= $f['value'] ?></strong>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <a href="<?= BASE_URL . $hub['public'] ?>" target="_blank" class="btn btn-light btn-action btn-sm hub-view"><i class="fas fa-external-link-alt"></i> Lihat halaman</a>
                </div>
                <nav class="hub-tabs" aria-label="Bagian halaman">
                    <?php foreach ($hub['tabs'] as $i => $tab) : ?>
                        <a href="<?= BASE_URL . $tab['url'] ?>" class="hub-tab <?= $i === $navCtx['tab'] ? 'active' : '' ?>"><i class="fas <?= $tab['icon'] ?>"></i><?= htmlspecialchars($tab['label']) ?></a>
                    <?php endforeach; ?>
                </nav>
            </div>
        <?php endif; ?>
