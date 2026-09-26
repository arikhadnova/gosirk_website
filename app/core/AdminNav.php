<?php
// app/core/AdminNav.php
// Admin navigation in one place: the sidebar, the tabs of each page/content "hub", the Ctrl+K search
// and the "Edit halaman ini" button on the public site all read from here.
//
// A hub groups every editor that belongs to one website page (or one kind of content). Each tab points
// to an existing admin screen; `match` tells which screen is "inside" the tab:
//   active   => value(s) of $data['active'] set by the controller
//   page     => value of ?page= (shared editors filtered to one page: hero, sections, texts, images, SEO)
//   category => value of $data['category'] (service items)

class AdminNav {
    const GROUPS = [
        'pages'    => 'Halaman Website',
        'content'  => 'Konten',
        'inbox'    => 'Kotak Masuk',
        'settings' => 'Pengaturan',
    ];

    public static function hubs() {
        $t = fn($label, $url, $match, $icon = null) => compact('label', 'url', 'match', 'icon');
        $common = function ($texts, $images, $seo) use ($t) {
            $tabs = [];
            if ($texts) $tabs[] = $t('Teks', "admin/page_texts?page=$texts", ['active' => 'page_texts', 'page' => $texts], 'fa-font');
            if ($images) $tabs[] = $t('Gambar', "admin/page_images?page=$images", ['active' => 'page_images', 'page' => $images], 'fa-images');
            if ($seo) $tabs[] = $t('SEO', "admin/seo?page=$seo", ['active' => 'seo', 'page' => $seo], 'fa-search');
            return $tabs;
        };
        $hero = fn($p) => $t('Hero', "admin/hero?page=$p", ['active' => 'hero', 'page' => $p], 'fa-image');
        $section = fn($p, $label = 'Tentang') => $t($label, "admin/page_sections?page=$p", ['active' => 'page_sections', 'page' => $p], 'fa-align-left');
        $impact = fn($p, $label = 'Data Dampak') => $t($label, "admin/impact/$p", ['active' => "impact_$p"], 'fa-chart-line');

        return [
            // ---- Website pages
            'home' => ['group' => 'pages', 'label' => 'Home', 'icon' => 'fa-home', 'public' => '', 'tabs' => array_merge([
                $hero('home'),
                $impact('home', 'Impact Footprint'),
                $t('Layanan Kami', 'admin/services', ['active' => 'services'], 'fa-concierge-bell'),
            ], $common('home', 'home', 'home'))],
            'about' => ['group' => 'pages', 'label' => 'About Us', 'icon' => 'fa-info-circle', 'public' => 'about', 'tabs' => array_merge([
                $section('about'),
                $t('Founder', 'admin/founders', ['active' => 'founders'], 'fa-user-tie'),
            ], $common('about', 'about', 'about'))],
            'gi' => ['group' => 'pages', 'label' => 'GoSirk Institute', 'icon' => 'fa-school', 'public' => 'gi', 'tabs' => array_merge([
                $hero('gi'),
                $section('gi'),
                $t('Layanan', 'admin/gi_services', ['active' => ['services_cb', 'gi_services']], 'fa-chalkboard-teacher'),
                $t('Video', 'admin/gi_videos', ['active' => 'gi_videos'], 'fa-video'),
                $impact('gi'),
            ], $common('gi', null, 'gi'))],
            'ggc' => ['group' => 'pages', 'label' => 'GoSirk Green Community', 'icon' => 'fa-leaf', 'public' => 'ggc', 'tabs' => array_merge([
                $hero('ggc'),
                $section('ggc'),
                $t('Aksi GGC', 'admin/ggc_actions', ['active' => 'ggc_actions'], 'fa-running'),
                $impact('ggc'),
            ], $common('ggc', 'ggc', 'ggc'))],
            'gnp' => ['group' => 'pages', 'label' => 'Go Ngompos Project', 'icon' => 'fa-seedling', 'public' => 'go_ngompos_project', 'tabs' => array_merge([
                $hero('go_ngompos_project'),
                $section('go_ngompos_project'),
                $t('Program', 'admin/gnp_programs', ['active' => 'gnp_programs'], 'fa-list-check'),
                $impact('go_ngompos_project'),
            ], $common('go_ngompos_project', 'gnp', 'go_ngompos_project'))],
            'partner' => ['group' => 'pages', 'label' => 'Implementasi Partner', 'icon' => 'fa-handshake', 'public' => 'implementasi_partner', 'tabs' => array_merge([
                $hero('partner'),
                $section('partner'),
                $t('Layanan', 'admin/services_pd', ['category' => 'pd'], 'fa-layer-group'),
                $impact('clocc', 'Dampak CLOCC'),
                $t('Desa Pilot', 'admin/pilot_villages', ['active' => 'pilot_villages'], 'fa-map-marker-alt'),
                $t('Sorotan', 'admin/partner_highlights', ['active' => 'partner_highlights'], 'fa-star'),
            ], $common('implentasi_partner', 'partner', 'implementasi_partner'))],
            'konsultan' => ['group' => 'pages', 'label' => 'Konsultansi', 'icon' => 'fa-lightbulb', 'public' => 'konsultan', 'tabs' => array_merge([
                $hero('konsultan'),
                $section('konsultan'),
                $t('Layanan', 'admin/services_cs', ['category' => 'cs'], 'fa-layer-group'),
            ], $common('konsultan', 'konsultan', 'konsultan'))],
            'partnership' => ['group' => 'pages', 'label' => 'Partnership', 'icon' => 'fa-people-group', 'public' => 'partnership', 'tabs' => array_merge([
                $t('Jenis Kemitraan', 'admin/partnership_settings', ['active' => 'partnership_settings'], 'fa-users-cog'),
            ], $common('partnership', 'partnership', 'partnership'))],
            'contact' => ['group' => 'pages', 'label' => 'Contact', 'icon' => 'fa-address-book', 'public' => 'contact', 'tabs' => $common('contact', 'contact', 'contact')],

            // ---- Content used on several pages
            'portfolio' => ['group' => 'content', 'label' => 'Portofolio', 'icon' => 'fa-briefcase', 'public' => 'partnership', 'tabs' => array_merge([
                $t('Daftar Portofolio', 'admin/portfolio', ['active' => 'portfolio'], 'fa-briefcase'),
            ], $common('portfolio', 'portfolio', null))],
            'blog' => ['group' => 'content', 'label' => 'Artikel Blog', 'icon' => 'fa-newspaper', 'public' => 'blog', 'tabs' => array_merge([
                $t('Artikel', 'admin/articles', ['active' => 'articles'], 'fa-newspaper'),
            ], $common('blog', null, 'blog'))],
            'library' => ['group' => 'content', 'label' => 'Library', 'icon' => 'fa-book', 'public' => 'library', 'tabs' => array_merge([
                $t('Resource', 'admin/library', ['active' => 'library'], 'fa-book'),
            ], $common('library', null, 'library'))],
            'publication' => ['group' => 'content', 'label' => 'Publikasi', 'icon' => 'fa-book-open', 'public' => 'publication', 'tabs' => array_merge([
                $t('Publikasi', 'admin/publications', ['active' => 'publications'], 'fa-book-open'),
            ], $common('publication', null, 'publication'))],
            'documents' => ['group' => 'content', 'label' => 'Dokumen Kolaborasi', 'icon' => 'fa-file-shield', 'public' => 'collaboration', 'tabs' => array_merge([
                $t('Dokumen', 'admin/collaboration', ['active' => 'collaboration'], 'fa-file-shield'),
            ], $common('collaboration', null, 'collaboration'))],
            'partners' => ['group' => 'content', 'label' => 'Partner & Logo', 'icon' => 'fa-handshake-angle', 'public' => '', 'tabs' => [
                $t('Partner & Logo', 'admin/partners', ['active' => 'partners'], 'fa-handshake-angle'),
            ]],

            // ---- Settings
            'site' => ['group' => 'settings', 'label' => 'Situs', 'icon' => 'fa-globe', 'public' => '', 'tabs' => [
                $t('Header & Footer', 'admin/settings', ['active' => 'settings'], 'fa-layer-group'),
                $t('Teks Navigasi & Footer', 'admin/page_texts?page=layouts', ['active' => 'page_texts', 'page' => 'layouts'], 'fa-font'),
                $t('Kebijakan Privasi', 'admin/privacy', ['active' => 'privacy'], 'fa-user-shield'),
                $t('SEO Kebijakan Privasi', 'admin/seo?page=privacy', ['active' => 'seo', 'page' => 'privacy'], 'fa-search'),
                $t('Optimasi Gambar', 'admin/image_optimize', ['active' => 'image_optimize'], 'fa-bolt'),
            ]],
        ];
    }

    /** Single sidebar items that are not hubs: [key, label, icon, url, active values, badge key, admin only] */
    public static function items() {
        return [
            'inbox' => [
                ['contacts', 'Pesan Kontak', 'fa-envelope', 'admin/contacts', ['contacts'], 'messages', true],
                ['requests', 'Permintaan Dokumen', 'fa-file-signature', 'admin/collaboration_requests', ['collaboration_requests'], 'requests', false],
            ],
            'settings' => [
                ['email', 'Email', 'fa-at', 'admin/email_settings', ['email_settings'], null, false],
                ['maintenance', 'Mode Pemeliharaan', 'fa-tools', 'admin/maintenance', ['maintenance'], null, false],
                ['users', 'Akun Pengguna', 'fa-users-gear', 'admin/users', ['users'], null, true],
            ],
        ];
    }

    /** Hub and tab for the current screen, or null. */
    public static function context($data) {
        $active = $data['active'] ?? null;
        $category = $data['category'] ?? null;
        $page = $data['only'] ?? ($_GET['page'] ?? null);
        foreach (self::hubs() as $key => $hub) {
            foreach ($hub['tabs'] as $i => $tab) {
                $m = $tab['match'];
                if (isset($m['active']) && !in_array($active, (array) $m['active'], true)) continue;
                if (isset($m['category']) && $m['category'] !== $category) continue;
                if (!isset($m['active']) && !isset($m['category'])) continue;
                if (isset($m['page']) && $m['page'] !== $page) continue;
                return ['key' => $key, 'hub' => $hub, 'tab' => $i];
            }
        }
        return null;
    }

    /**
     * Quick facts about a hub for its header and the dashboard:
     * [['label' => ..., 'value' => ..., 'ok' => bool|null, 'url' => ...], ...]
     */
    public static function status($hub) {
        static $settings = null;
        if ($settings === null) {
            if (!class_exists('Setting_model')) require_once dirname(__DIR__) . '/models/Setting_model.php';
            $settings = (new Setting_model())->getAll();
        }
        $out = [];
        foreach ($hub['tabs'] as $tab) {
            $page = $tab['match']['page'] ?? null;
            $active = $tab['match']['active'] ?? null;
            if ($active === 'seo' && $page) {
                $set = trim($settings["seo.$page.title"] ?? '') !== '' || trim($settings["seo.$page.description"] ?? '') !== '';
                $out[] = ['label' => 'SEO', 'value' => $set ? 'Diatur' : 'Bawaan', 'ok' => $set, 'url' => $tab['url']];
            }
            if ($active === 'page_images' && $page && isset(PageImages::PAGES[$page])) {
                $total = 0; $custom = 0;
                foreach (PageImages::SLOTS as $key => [$group]) {
                    if (!in_array($group, PageImages::PAGES[$page], true)) continue;
                    $total++;
                    if (PageImages::custom($key)) $custom++;
                }
                $out[] = ['label' => 'Gambar diganti', 'value' => "$custom/$total", 'ok' => null, 'url' => $tab['url']];
            }
        }
        return $out;
    }

    /** Everything the Ctrl+K search can jump to: [label, hint, url, keywords] */
    public static function searchIndex($isAdmin) {
        $out = [];
        foreach (self::hubs() as $hub) {
            $group = self::GROUPS[$hub['group']];
            foreach ($hub['tabs'] as $tab) {
                $out[] = [$hub['label'] . ' › ' . $tab['label'], $group, BASE_URL . $tab['url'], ''];
            }
        }
        foreach (self::items() as $group => $items) {
            foreach ($items as [$key, $label, $icon, $url, $actives, $badge, $adminOnly]) {
                if ($adminOnly && !$isAdmin) continue;
                $out[] = [$label, self::GROUPS[$group], BASE_URL . $url, ''];
            }
        }
        // Words people type that are not in the menu names
        $extra = [
            ['Nomor WhatsApp, email & alamat kantor', 'Pengaturan › Situs', 'admin/settings', 'whatsapp wa telepon alamat kontak sosial media instagram linkedin footer'],
            ['Logo & judul situs', 'Pengaturan › Situs', 'admin/settings', 'logo favicon judul title deskripsi'],
            ['API key & template email', 'Pengaturan › Email', 'admin/email_settings', 'brevo smtp template notifikasi'],
            ['Ganti password / foto profil', 'Akun', 'admin/profile', 'password kata sandi profil foto akun saya'],
            ['Logo mitra CLOCC', 'Implementasi Partner › Gambar', 'admin/page_images?page=partner', 'clocc logo mitra'],
            ['Company Profile, Executive Summary, Concept Note', 'Konten › Dokumen Kolaborasi', 'admin/collaboration', 'pdf company profile executive summary concept note'],
            ['Kategori artikel', 'Konten › Artikel Blog', 'admin/articles', 'kategori category tag'],
        ];
        foreach ($extra as [$label, $hint, $url, $kw]) $out[] = [$label, $hint, BASE_URL . $url, $kw];
        return $out;
    }

    /** Admin URL for the public page being viewed (for the "Edit halaman ini" button). */
    public static function editUrlFor(array $segments) {
        [$first, $second, $third] = array_pad($segments, 3, '');
        if ($second === 'detail' && $third !== '') {
            if ($first === 'blog' && ctype_digit($third)) return BASE_URL . 'admin/articles_edit/' . $third;
            if ($first === 'portfolio' && ctype_digit($third)) return BASE_URL . 'admin/portfolio_edit/' . $third;
            if ($first === 'gi') return BASE_URL . 'admin/gi_services';
        }
        $map = ['' => 'home', 'home' => 'home', 'about' => 'about', 'gi' => 'gi', 'ggc' => 'ggc', 'go_ngompos_project' => 'gnp',
                'implementasi_partner' => 'partner', 'konsultan' => 'konsultan', 'partnership' => 'partnership', 'contact' => 'contact',
                'blog' => 'blog', 'library' => 'library', 'publication' => 'publication', 'collaboration' => 'documents', 'portfolio' => 'portfolio'];
        if ($first === 'home' && $second === 'impact') return BASE_URL . 'admin/impact/home';
        $hub = self::hubs()[$map[$first] ?? 'home'];
        return BASE_URL . $hub['tabs'][0]['url'];
    }
}
