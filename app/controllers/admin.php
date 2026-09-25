<?php
require_once dirname(dirname(__DIR__)) . '/app/core/Translator.php';

class Admin extends Controller {
    private $portfolioModel;
    private $articleModel;
    private $publicationModel;
    private $serviceModel;
    private $impactModel;
    private $partnerModel;
    private $userModel;
    private $settingModel;
    private $serviceItemModel;
    private $collaborationModel;
    private $heroModel;
    private $testimonialModel;
    private $contactModel;
    private $giServiceModel;
    private $faqModel;
    private $giVideoModel;
    private $founderModel;
    private $activityLogModel;
    private $pilotVillageModel;
    private $ggcActionModel;
    private $gnpProgramModel;
    private $pageTextModel;
    private $pageSectionModel;

    public function __construct() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        // PHP drops the whole request body when it exceeds post_max_size
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
            Flasher::setFlash('Upload gagal.', 'Total ukuran file melebihi batas server (' . ini_get('post_max_size') . '). Perkecil ukuran file lalu coba lagi.', 'danger');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin'));
            exit;
        }
        // CSRF: forms need the session token; links that delete/change data must come from the admin itself
        $path = strtolower($_GET['url'] ?? '');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validRequest()) Csrf::reject($this->safeBackUrl());
        } elseif (preg_match('#delete|_read/#', $path) && !Csrf::sameOrigin()) {
            Csrf::reject(BASE_URL . 'admin');
        }
        $this->portfolioModel = $this->model('Portfolio_model');
        $this->articleModel = $this->model('Article_model');
        $this->publicationModel = $this->model('Publication_model');
        $this->serviceModel = $this->model('Service_model');
        $this->impactModel = $this->model('Impact_model');
        $this->partnerModel = $this->model('Partner_model');
        $this->userModel = $this->model('User_model');
        $this->settingModel = $this->model('Setting_model');
        $this->serviceItemModel = $this->model('ServiceItem_model');
        $this->collaborationModel = $this->model('Collaboration_model');
        $this->heroModel = $this->model('Hero_model');
        $this->testimonialModel = $this->model('Testimonial_model');
        $this->contactModel = $this->model('Contact_model');
        $this->giServiceModel = $this->model('GiService_model');
        $this->faqModel = $this->model('Faq_model');
        $this->giVideoModel = $this->model('GiVideo_model');
        $this->giVideoModel = $this->model('GiVideo_model');
        $this->founderModel = $this->model('Founder_model');
        $this->activityLogModel = $this->model('ActivityLog_model');
        $this->pilotVillageModel = $this->model('PilotVillage_model');
        $this->ggcActionModel = $this->model('GgcAction_model');
        $this->gnpProgramModel = $this->model('GnpProgram_model');
        $this->pageTextModel = $this->model('PageText_model');
        $this->pageSectionModel = $this->model('PageSection_model');

        // Auto-initialize settings table
        $db = new Database();
        $db->query("CREATE TABLE IF NOT EXISTS `settings` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `setting_key` varchar(100) NOT NULL,
          `setting_value` text DEFAULT NULL,
          `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
          PRIMARY KEY (`id`),
          UNIQUE KEY `setting_key` (`setting_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $db->execute();
    }

    // Shared editors (hero, sections, texts, images, SEO) show one page at a time: ?page=<key>.
    // Without a valid key they redirect to the first page, so every screen belongs to a page hub.
    private function onlyPage(array $allowed, $screen) {
        $page = $_GET['page'] ?? '';
        if (in_array($page, $allowed, true)) return $page;
        header('Location: ' . BASE_URL . 'admin/' . $screen . '?page=' . rawurlencode($allowed[0]));
        exit;
    }

    // Referer inside this site, otherwise the dashboard
    private function safeBackUrl() {
        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        return strpos($ref, BASE_URL) === 0 ? $ref : BASE_URL . 'admin';
    }

    // Items that need an admin's attention (sidebar badges, topbar bell, dashboard). Cached per request.
    public function adminAlerts() {
        static $alerts = null;
        if ($alerts !== null) return $alerts;
        $followup = $this->collaborationModel->getAllRequests('followup');
        return $alerts = [
            'requests' => count($followup),
            'messages' => (int) $this->contactModel->getUnreadCount(),
            'latest_requests' => array_slice($followup, 0, 5),
        ];
    }

    // Validate the submitted admin form against FormRules; on failure go back with the errors
    private function validateForm($form, $mode = 'store') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        FormRules::normalize($form, $_POST);
        $errors = FormRules::validate($form, $_POST, $_FILES, $mode);
        if (!empty($errors)) {
            Flasher::keepOldInput($_POST);
            Flasher::setFlash('Data belum valid.', 'Periksa kembali isian berikut:', 'danger', $errors);
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin'));
            exit;
        }
    }

    public function index() {
        $contactModel = $this->model('Contact_model');
        
        // Fetch activity logs
        $activities = $this->activityLogModel->getRecent(15);
        
        // Format for view compatibility if needed, but view will change to use this structure directly
        // The view expects objects with: user_name, action_type, target_type, description, created_at

        $data = [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'activities' => $activities,
            'counts' => [
                'doc_requests' => count($this->collaborationModel->getAllRequests()),
                'doc_followup' => $this->adminAlerts()['requests'],
                'articles' => count($this->articleModel->getAll()),
                'publications' => count($this->publicationModel->getAll()),
                'partners' => count($this->partnerModel->getAll()),
                'documents' => count($this->collaborationModel->getAllDocuments()),
                'unread_messages' => $contactModel->getUnreadCount(),
                'total_visitors' => $this->model('Visitor_model')->getTotalCount(),
                'visitor_stats' => $this->model('Visitor_model')->getMonthlyStats()
            ]
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/dashboard', $data);
        $this->views('layouts/admin_footer');
    }

    public function profile() {
        $this->validateForm(isset($_POST['new_password']) ? 'password_change' : 'profile', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'];
            $user = $this->userModel->getUserById($user_id);

            // Check if it's personal info or security update
            if (isset($_POST['name']) && isset($_POST['username'])) {
                // Personal Information Update
                $data = [
                    'name' => $_POST['name'],
                    'username' => $_POST['username']
                ];

                if ($this->userModel->updateProfile($user_id, $data)) {
                    $_SESSION['user_name'] = $data['name']; // Update session name
                    $this->activityLogModel->log('UPDATE', 'Profile', "Memperbarui informasi profil pengguna {$data['username']}");
                    Flasher::setFlash('Profil', 'berhasil diperbarui', 'success');
                } else {
                    Flasher::setFlash('Profil', 'gagal diperbarui', 'danger');
                }
            } elseif (isset($_POST['current_password'])) {
                // Security Update
                $current = $_POST['current_password'];
                $new = $_POST['new_password'];
                $confirm = $_POST['confirm_password'];

                if (password_verify($current, $user->password)) {
                    if ($new === $confirm) {
                        if (strlen($new) >= 8) {
                            $data = [
                                'name' => $user->name,
                                'username' => $user->username,
                                'password' => $new
                            ];
                            if ($this->userModel->updateProfile($user_id, $data)) {
                                $this->activityLogModel->log('UPDATE', 'Profile', "Mengubah password pengguna {$user->username}");
                                Flasher::setFlash('Password', 'berhasil diperbarui', 'success');
                            } else {
                                Flasher::setFlash('Password', 'gagal diperbarui', 'danger');
                            }
                        } else {
                            Flasher::setFlash('Password baru', 'minimal 8 karakter', 'danger');
                        }
                    } else {
                        Flasher::setFlash('Konfirmasi password', 'tidak cocok', 'danger');
                    }
                } else {
                    Flasher::setFlash('Password saat ini', 'salah', 'danger');
                }
            }
            header('Location: ' . BASE_URL . 'admin/profile');
            exit;
        }

        $data = [
            'title' => 'Profil Akun',
            'active' => 'profile',
            'user' => $this->userModel->getUserById($_SESSION['user_id'])
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/profile', $data);
        $this->views('layouts/admin_footer');
    }

    public function settings() {
        $data = [
            'title' => 'Pengaturan Layout',
            'active' => 'settings',
            'settings' => $this->settingModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/settings_layout', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_header() {
        $this->validateForm('settings_header', 'update');
        $site_title = $_POST['site_title'] ?? '';
        $site_description = $_POST['site_description'] ?? '';
        $this->settingModel->update('site_title', $site_title);
        $this->settingModel->update('site_description', $site_description);
        
        // Handle Logo Upload if any
        if (!empty($_FILES['logo_file']['name'])) {
            // Kept in its original format: the logo is also the link preview image (og:image) for other sites
            $newLogo = Upload::file($_FILES['logo_file'], 'img', ['jpg', 'jpeg', 'png', 'gif', 'webp'], null, false);
            if ($newLogo) {
                // Optional: Delete old logo if it's not the default one
                $oldLogo = $this->settingModel->getByKey('site_logo');
                if ($oldLogo && $oldLogo != 'Logo-GoSirk-01.png') {
                    Upload::delete($oldLogo, 'img');
                }
                $this->settingModel->update('site_logo', $newLogo);
            }
        }
        
        Flasher::setFlash('Header', 'berhasil diperbarui', 'success');
        $this->activityLogModel->log('UPDATE', 'Settings', "Memperbarui Pengaturan Header Website");
        header('Location: ' . BASE_URL . 'admin/settings');
        exit;
    }

    public function update_footer() {
        $this->validateForm('settings_footer', 'update');
        $footer_data = [
            'footer_copyright' => $_POST['footer_text'] ?? '',
            'address_hq' => $_POST['address_hq'] ?? '',
            'address_branch' => $_POST['address_branch'] ?? '',
            'contact_email' => $_POST['contact_email'] ?? '',
            'contact_whatsapp' => $_POST['contact_whatsapp'] ?? '',
            'social_facebook' => $_POST['social_facebook'] ?? '',
            'social_instagram' => $_POST['social_instagram'] ?? '',
            'social_linkedin' => $_POST['social_linkedin'] ?? '',
            'social_youtube' => $_POST['social_youtube'] ?? ''
        ];
        $hours = trim($_POST['office_hours'] ?? '');
        $footer_data['office_hours'] = $hours;
        $footer_data['office_hours_en'] = $hours !== '' ? Translator::translate($hours) : '';
        
        foreach ($footer_data as $key => $value) {
            $this->settingModel->update($key, $value);
        }
        
        Flasher::setFlash('Footer', 'berhasil diperbarui', 'success');
        $this->activityLogModel->log('UPDATE', 'Settings', "Memperbarui Pengaturan Footer Website");
        header('Location: ' . BASE_URL . 'admin/settings');
        exit;
    }

    public function partnership_settings() {
        $data = [
            'title' => 'Pengaturan Partnership',
            'active' => 'partnership_settings',
            'settings' => $this->settingModel->getAll(),
            'portfolios' => $this->portfolioModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/partnership_settings', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_partnership_settings() {
        $this->validateForm('partnership_settings', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($_POST as $key => $value) {
                // Update the Indonesian version
                $this->settingModel->update($key, $value);

                // Auto-translate to English if it's an _id field
                if (strpos($key, '_id') !== false) {
                    $en_key = str_replace('_id', '_en', $key);
                    $en_value = Translator::translate($value);
                    $this->settingModel->update($en_key, $en_value);
                }
            }

            // Handle Image Uploads for Categories
            $image_keys = ['ps_comm_img', 'ps_acad_img', 'ps_prog_img'];
            foreach ($image_keys as $key) {
                if (!empty($_FILES[$key]['name'])) {
                    $newImage = Upload::file($_FILES[$key], 'img');
                    if ($newImage) {
                        $oldImage = $this->settingModel->getByKey($key);
                        if ($oldImage) {
                            Upload::delete($oldImage, 'img');
                        }
                        $this->settingModel->update($key, $newImage);
                    }
                }
            }

            Flasher::setFlash('Settings Partnership', 'berhasil diperbarui', 'success');
            $this->activityLogModel->log('UPDATE', 'Settings', "Memperbarui Pengaturan Partnership");
            header('Location: ' . BASE_URL . 'admin/partnership_settings');
            exit;
        }
    }

    private function getAboutSectionDefaults() {
        return [
            'about' => [
                'label' => 'About Page',
                'badge_id' => 'Tentang Kami',
                'badge_en' => 'About Us',
                'title_id' => 'Membangun Masa Depan Berkelanjutan',
                'title_en' => 'Building a Sustainable Future',
                'content_id' => 'PT Gocircular Solutions Indonesia (GoSirk) adalah perusahaan swasta dengan orientasi bisnis sosial yang kuat, didedikasikan untuk mengembangkan solusi inovatif dan ramah lingkungan dalam pengelolaan sampah.',
                'content_en' => 'PT Gocircular Solutions Indonesia (GoSirk) is a private company with a strong social business orientation, dedicated to developing innovative and environmentally friendly waste management solutions.',
                'content_2_id' => 'Kami berkomitmen untuk menciptakan sistem pengelolaan sampah yang berkelanjutan melalui implementasi bisnis sirkular dan program-program yang memberikan manfaat bagi lingkungan serta memberdayakan usaha lokal di sektor pengelolaan sampah.',
                'content_2_en' => 'We are committed to creating sustainable waste management systems through circular business implementation and programs that benefit the environment while empowering local waste management businesses.',
                'content_3_id' => 'Kami percaya pada kekuatan kolaborasi dan komunitas, bekerja bersama dengan usaha-usaha lokal untuk mendorong pertumbuhan, menciptakan lapangan kerja, dan meningkatkan standar praktik pengelolaan sampah.',
                'content_3_en' => 'We believe in the power of collaboration and community, working together with local enterprises to encourage growth, create jobs, and improve waste management practice standards.',
                'image' => 'about-2.jpg'
            ],
            'gi' => [
                'label' => 'GoSirk Institute',
                'badge_id' => 'Tentang Kami',
                'badge_en' => 'About Us',
                'title_id' => 'Membangun Ekosistem Pengetahuan Sirkular',
                'title_en' => 'Building a Circular Knowledge Ecosystem',
                'content_id' => 'GoSirk Institute merupakan bagian dari unit strategis dalam ekosistem bisnis PT GO Circular Solutions Indonesia (GoSirk) dalam membangun sistem manajemen pengetahuan yang terstruktur di bidang pengelolaan sampah. Sebagai unit khusus dalam lini usaha Capacity Building, GoSirk Institute menjadi wadah untuk mengembangkan dan menyebarluaskan pembelajaran, praktik baik, serta inovasi-inovasi yang lahir dari pengalaman nyata di lapangan.',
                'content_en' => 'GoSirk Institute is part of a strategic unit within PT GO Circular Solutions Indonesia (GoSirk) business ecosystem to build a structured knowledge management system in waste management. As a dedicated Capacity Building unit, GoSirk Institute serves as a platform to develop and share learning, good practices, and innovations born from real field experience.',
                'content_2_id' => '',
                'content_2_en' => '',
                'content_3_id' => '',
                'content_3_en' => '',
                'image' => 'gi-1.jpeg'
            ],
            'ggc' => [
                'label' => 'GoSirk Green Community',
                'badge_id' => 'SIAPA KAMI?',
                'badge_en' => 'WHO ARE WE?',
                'title_id' => 'MENGENAL <span class="text-success">GOSIRK GREEN COMMUNITY</span>',
                'title_en' => 'GET TO KNOW <span class="text-success">GOSIRK GREEN COMMUNITY</span>',
                'content_id' => 'GoSirk Green Community adalah inisiatif unggulan yang digagas oleh PT Go Circular Solutions Indonesia (GoSirk) yang menghadirkan solusi nyata dalam pengelolaan sampah berbasis komunitas.',
                'content_en' => 'GoSirk Green Community is a flagship initiative by PT Go Circular Solutions Indonesia (GoSirk) that delivers real solutions for community-based waste management.',
                'content_2_id' => 'Melalui pendekatan partisipatif, edukatif, dan kolaborasi lintas sektor, program ini mendorong transformasi sosial dan pelestarian lingkungan di tingkat desa dan kelurahan.',
                'content_2_en' => 'Through participatory, educational, and cross-sector collaboration, this program encourages social transformation and environmental preservation at village and urban community levels.',
                'content_3_id' => '',
                'content_3_en' => '',
                'image' => 'IMG_8093-crop.jpg'
            ],
            'go_ngompos_project' => [
                'label' => 'Go Ngompos Project',
                'badge_id' => 'TENTANG PROGRAM',
                'badge_en' => 'ABOUT THE PROGRAM',
                'title_id' => 'MENGENAL <span class="text-success">GO NGOMPOS PROJECT</span>',
                'title_en' => 'GET TO KNOW <span class="text-success">GO NGOMPOS PROJECT</span>',
                'content_id' => 'Go Ngompos Project adalah inisiatif GoSirk untuk mengajak masyarakat mengurangi sampah organik yang terbuang ke TPA melalui praktik pengomposan yang mudah dan dekat dengan keseharian.',
                'content_en' => 'Go Ngompos Project is a GoSirk initiative inviting people to reduce organic waste sent to landfills through simple composting practices close to daily life.',
                'content_2_id' => 'Program ini menggabungkan edukasi, pendampingan, dan kampanye perubahan perilaku agar rumah tangga, sekolah, kantor, dan komunitas mampu mengelola sampah organiknya sendiri.',
                'content_2_en' => 'This program combines education, assistance, and behavior change campaigns so households, schools, offices, and communities can manage their own organic waste.',
                'content_3_id' => '',
                'content_3_en' => '',
                'image' => 'https://images.unsplash.com/photo-1591857177580-dc82b9ac4e1e?auto=format&fit=crop&q=80&w=900'
            ],
            'konsultan' => [
                'label' => 'Konsultan',
                'badge_id' => 'Tentang Layanan Kami',
                'badge_en' => 'About Our Service',
                'title_id' => 'Solusi Strategis Berbasis Data dan Pengalaman Lapangan',
                'title_en' => 'Strategic Solutions Based on Data and Field Experience',
                'content_id' => 'GoSirk menyediakan layanan konsultansi profesional dan berorientasi solusi untuk memperkuat sistem pengelolaan sampah di Indonesia, didukung rekam jejak solid sejak 2022 dalam menyusun kebijakan strategis dan inovasi pembiayaan.',
                'content_en' => 'GoSirk provides professional, solution-oriented consulting services to strengthen waste management systems in Indonesia, supported by a solid track record since 2022 in strategic policy development and financing innovation.',
                'content_2_id' => '',
                'content_2_en' => '',
                'content_3_id' => '',
                'content_3_en' => '',
                'image' => ''
            ],
            'partner' => [
                'label' => 'Implementasi Partner',
                'badge_id' => 'MITRA PENGEMBANGAN',
                'badge_en' => 'DEVELOPMENT PARTNER',
                'title_id' => 'Tentang Layanan Kami',
                'title_en' => 'About Our Service',
                'content_id' => 'GO Sirk berperan sebagai <b>mitra pengembangan dan implementasi proyek</b> untuk mentransformasi sampah menjadi solusi yang <b>berkelanjutan, inklusif, dan inovatif</b>. Kami mendampingi mitra sejak tahap perencanaan hingga pelaksanaan di lapangan untuk memastikan proyek berjalan <b>efektif secara teknis</b>, terukur, serta menghasilkan <b>dampak sosial dan lingkungan</b> yang nyata.',
                'content_en' => 'GO Sirk acts as a <b>project development and implementation partner</b> to transform waste into <b>sustainable, inclusive, and innovative</b> solutions. We assist partners from planning to field execution to ensure projects run <b>effectively from a technical standpoint</b>, are measurable, and create real <b>social and environmental impact</b>.',
                'content_2_id' => '<b>Fokus utama</b> kami adalah memastikan keberhasilan implementasi melalui penguatan kolaborasi, tata kelola, dan model operasional yang relevan dengan konteks lokal.',
                'content_2_en' => 'Our <b>main focus</b> is ensuring implementation success through stronger collaboration, governance, and operating models relevant to the local context.',
                'content_3_id' => '',
                'content_3_en' => '',
                'image' => ''
            ]
        ];
    }

    public function page_sections() {
        $defaults = $this->getAboutSectionDefaults();
        $sections = [];

        foreach ($defaults as $page => $default) {
            $stored = $this->pageSectionModel->getByPageAndSection($page, 'about');
            $sections[$page] = (object) array_merge($default, $stored ? (array) $stored : [
                'page_name' => $page,
                'section_key' => 'about',
                'is_active' => 1
            ]);
        }

        $data = [
            'title' => 'Section Halaman',
            'active' => 'page_sections',
            'only' => $this->onlyPage(array_keys($defaults), 'page_sections'),
            'sections' => $sections,
            'pages' => array_map(fn($item) => $item['label'], $defaults)
        ];

        $this->views('layouts/admin_header', $data);
        $this->views('admin/page_sections', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_page_section() {
        $this->validateForm('page_section', 'update');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/page_sections');
            exit;
        }

        $page = $_POST['page_name'] ?? '';
        $defaults = $this->getAboutSectionDefaults();
        if (!$page || !isset($defaults[$page])) {
            Flasher::setFlash('Page Section', 'tidak valid', 'danger');
            header('Location: ' . BASE_URL . 'admin/page_sections');
            exit;
        }

        $oldSection = $this->pageSectionModel->getByPageAndSection($page, 'about');
        $image = $_POST['existing_image'] ?? ($defaults[$page]['image'] ?? '');

        if (!empty($_FILES['image']['name'])) {
            $newImage = Upload::file($_FILES['image'], 'img', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
            if ($newImage) {
                $oldImage = $oldSection->image ?? '';
                if ($oldImage && !filter_var($oldImage, FILTER_VALIDATE_URL) && !in_array($oldImage, array_column($defaults, 'image'))) {
                    Upload::delete($oldImage, 'img');
                }
                $image = $newImage;
            }
        }

        $sectionData = [
            'page_name' => $page,
            'section_key' => 'about',
            'badge_id' => $_POST['badge_id'] ?? '',
            'badge_en' => Translator::translate($_POST['badge_id'] ?? ''),
            'title_id' => $_POST['title_id'] ?? '',
            'title_en' => Translator::translate(strip_tags($_POST['title_id'] ?? '')),
            'content_id' => $_POST['content_id'] ?? '',
            'content_en' => Translator::translate(strip_tags($_POST['content_id'] ?? '')),
            'content_2_id' => $_POST['content_2_id'] ?? '',
            'content_2_en' => Translator::translate(strip_tags($_POST['content_2_id'] ?? '')),
            'content_3_id' => $_POST['content_3_id'] ?? '',
            'content_3_en' => Translator::translate(strip_tags($_POST['content_3_id'] ?? '')),
            'image' => $image,
            'is_active' => 1
        ];

        if ($this->pageSectionModel->upsert($sectionData)) {
            $this->activityLogModel->log('UPDATE', 'Page Section', "Memperbarui section About halaman '{$page}'");
            Flasher::setFlash('Page Section', 'berhasil diperbarui', 'success');
        } else {
            Flasher::setFlash('Page Section', 'gagal diperbarui', 'danger');
        }

        header('Location: ' . BASE_URL . 'admin/page_sections?page=' . rawurlencode($page));
        exit;
    }

    public function hero() {
        $heroes = $this->heroModel->getAll();
        $hero_data = [];
        foreach ($heroes as $h) {
            $hero_data[$h->page_name] = $h;
        }

        if (!isset($hero_data['go_ngompos_project'])) {
            $defaultHero = [
                'page_name' => 'go_ngompos_project',
                'tag_id' => '',
                'tag_en' => '',
                'title_id' => 'Go Ngompos Project',
                'title_en' => 'Go Ngompos Project',
                'subtitle_id' => 'Gerakan pengolahan sampah organik menjadi kompos dari rumah, sekolah, kantor, dan komunitas.',
                'subtitle_en' => 'A movement to turn organic waste into compost from homes, schools, offices, and communities.',
                'image' => 'https://images.unsplash.com/photo-1589923188900-85dae523342b?auto=format&fit=crop&q=80&w=900'
            ];
            $this->heroModel->insert($defaultHero);
            $hero_data['go_ngompos_project'] = (object) $defaultHero;
        }

        $hero_transitions = [];
        $hero_logos = [
            'ggc' => $this->settingModel->getByKey('ggc_hero_logo') ?: 'logo-ggc.png',
            'go_ngompos_project' => $this->settingModel->getByKey('go_ngompos_project_hero_logo') ?: 'logo-go-ngompos.svg'
        ];
        foreach (array_keys($hero_data) as $pageName) {
            $transitionKey = $pageName === 'home' ? 'home_hero_transition' : $pageName . '_hero_transition';
            $hero_transitions[$pageName] = $this->settingModel->getByKey($transitionKey) ?: 'slide';
        }

        $data = [
            'title' => 'Banner Utama (Hero)',
            'active' => 'hero',
            'only' => $this->onlyPage(['home', 'partner', 'konsultan', 'gi', 'ggc', 'go_ngompos_project'], 'hero'),
            'heroes' => $hero_data,
            'hero_transitions' => $hero_transitions,
            'hero_logos' => $hero_logos
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/hero', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_hero() {
        $this->validateForm('hero', 'update');
        $page = $_POST['page_name'] ?? '';
        if (!$page) {
            header('Location: ' . BASE_URL . 'admin/hero');
            exit;
        }

        $data = [
            'tag_id' => $_POST['tag_id'] ?? '',
            'tag_en' => Translator::translate($_POST['tag_id'] ?? ''),
            'title_id' => $_POST['title_id'] ?? '',
            'title_en' => Translator::translate($_POST['title_id'] ?? ''),
            'subtitle_id' => $_POST['subtitle_id'] ?? '',
            'subtitle_en' => Translator::translate($_POST['subtitle_id'] ?? '')
        ];

        // Handle Hero Slider Upload
        if (true) {
            $transition = $_POST['hero_transition'] ?? 'slide';
            $transitionKey = $page === 'home' ? 'home_hero_transition' : $page . '_hero_transition';
            $this->settingModel->update($transitionKey, in_array($transition, ['slide', 'fade']) ? $transition : 'slide');

            $oldHero = $this->heroModel->getByPage($page);
            $oldImages = [];
            if ($oldHero && $oldHero->image) {
                $decodedImages = json_decode($oldHero->image, true);
                $oldImages = is_array($decodedImages) ? $decodedImages : [$oldHero->image];
                $oldImages = array_values(array_filter(array_map('trim', $oldImages)));
            }

            $existingSlides = array_map('trim', $_POST['hero_existing_images'] ?? []);
            $finalSlides = [];
            for ($index = 0; $index < 5; $index++) {
                $existingImage = trim($existingSlides[$index] ?? '');
                $newImage = false;

                if (!empty($_FILES['hero_slide_images']['name'][$index])) {
                    $file = [
                        'name' => $_FILES['hero_slide_images']['name'][$index],
                        'type' => $_FILES['hero_slide_images']['type'][$index],
                        'tmp_name' => $_FILES['hero_slide_images']['tmp_name'][$index],
                        'error' => $_FILES['hero_slide_images']['error'][$index],
                        'size' => $_FILES['hero_slide_images']['size'][$index],
                    ];
                    $newImage = Upload::file($file, 'img');
                }

                $slideImage = $newImage ?: $existingImage;
                if ($slideImage && !filter_var($slideImage, FILTER_VALIDATE_URL) && !file_exists(__DIR__ . '/../../assets/img/' . $slideImage)) {
                    $slideImage = '';
                }
                if ($slideImage && count($finalSlides) < 5) {
                    $finalSlides[] = $slideImage;
                }
            }

            $defaults = ['hero-bg.jpg', 'IMG_8084.jpg', 'IMG_8082.jpg', 'petugas-baju-biru.png', 'banner-1.png', 'banner-2.png'];
            foreach ($oldImages as $oldImage) {
                if ($oldImage && !filter_var($oldImage, FILTER_VALIDATE_URL) && !in_array($oldImage, $finalSlides) && !in_array($oldImage, $defaults)) {
                    Upload::delete($oldImage, 'img');
                }
            }

            $data['image'] = !empty($finalSlides) ? json_encode($finalSlides) : '';
        } elseif (!empty($_FILES['hero_image']['name'])) {
            $newImage = Upload::file($_FILES['hero_image'], 'img');
            if ($newImage) {
                // Delete old image if it's not a URL and not a default asset
                $oldHero = $this->heroModel->getByPage($page);
                $oldImages = [];
                if ($oldHero && $oldHero->image) {
                    $decodedImages = json_decode($oldHero->image, true);
                    $oldImages = is_array($decodedImages) ? $decodedImages : [$oldHero->image];
                }

                // Check if it's not a seeded default image that might be shared
                $defaults = ['hero-bg.jpg', 'IMG_8084.jpg', 'IMG_8082.jpg', 'petugas-baju-biru.png', 'banner-1.png', 'banner-2.png'];
                foreach ($oldImages as $oldImage) {
                    if ($oldImage && !filter_var($oldImage, FILTER_VALIDATE_URL) && !in_array($oldImage, $defaults)) {
                        Upload::delete($oldImage, 'img');
                    }
                }
                $data['image'] = $newImage;
            }
        } elseif (!empty($_POST['remove_hero_image'])) {
            $oldHero = $this->heroModel->getByPage($page);
            $oldImages = [];
            if ($oldHero && $oldHero->image) {
                $decodedImages = json_decode($oldHero->image, true);
                $oldImages = is_array($decodedImages) ? $decodedImages : [$oldHero->image];
            }

            $defaults = ['hero-bg.jpg', 'IMG_8084.jpg', 'IMG_8082.jpg', 'petugas-baju-biru.png', 'banner-1.png', 'banner-2.png'];
            foreach ($oldImages as $oldImage) {
                if ($oldImage && !filter_var($oldImage, FILTER_VALIDATE_URL) && !in_array($oldImage, $defaults)) {
                    Upload::delete($oldImage, 'img');
                }
            }
            $data['image'] = '';
        }

        if (in_array($page, ['ggc', 'go_ngompos_project']) && !empty($_FILES['hero_logo']['name'])) {
            $newLogo = Upload::file($_FILES['hero_logo'], 'img', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
            if ($newLogo) {
                $logoKey = $page . '_hero_logo';
                $oldLogo = $this->settingModel->getByKey($logoKey);
                $defaultLogos = ['logo-ggc.png', 'logo-go-ngompos.svg'];
                if ($oldLogo && !filter_var($oldLogo, FILTER_VALIDATE_URL) && !in_array($oldLogo, $defaultLogos)) {
                    Upload::delete($oldLogo, 'img');
                }
                $this->settingModel->update($logoKey, $newLogo);
            }
        }

        if ($this->heroModel->update($page, $data)) {
            $this->activityLogModel->log('UPDATE', 'Hero Section', "Memperbarui Hero Section halaman '{$page}'");
            Flasher::setFlash('Hero ' . ucfirst($page), 'berhasil diperbarui', 'success');
        } else {
            Flasher::setFlash('Hero ' . ucfirst($page), 'gagal diperbarui', 'danger');
        }

        header('Location: ' . BASE_URL . 'admin/hero?page=' . rawurlencode($page));
        exit;
    }

    // Founders Management
    public function founders($action = null, $id = null) {
        if ($action === 'create') return $this->founders_create();
        if ($action === 'edit' && $id) return $this->founders_edit($id);
        if ($action === 'delete' && $id) return $this->founders_delete($id);

        $data = [
            'title' => 'Kelola Founder',
            'active' => 'founders',
            'founders' => $this->founderModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/founders', $data);
        $this->views('layouts/admin_footer');
    }

    public function founders_create() {
        $data = [
            'title' => 'Tambah Founder',
            'active' => 'founders'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/founders_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function founders_store() {
        $this->validateForm('founder', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image = '';
            if (!empty($_FILES['image']['name'])) {
                $image = Upload::file($_FILES['image'], 'img');
            }

            $data = [
                'name' => $_POST['name'],
                'role_id' => $_POST['role_id'],
                'role_en' => Translator::translate($_POST['role_id']),
                'quote_id' => $_POST['quote_id'],
                'quote_en' => Translator::translate($_POST['quote_id']),
                'linkedin_url' => $_POST['linkedin_url'],
                'display_order' => $_POST['display_order'] ?? 0,
                'image' => $image
            ];

            if ($this->founderModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Founder', "Menambahkan founder baru '{$_POST['name']}'");
                Flasher::setFlash('Founder', 'berhasil ditambahkan', 'success');
            } else {
                Flasher::setFlash('Founder', 'gagal ditambahkan', 'danger');
            }
            header('Location: ' . BASE_URL . 'admin/founders');
            exit;
        }
    }

    public function founders_edit($id) {
        $data = [
            'title' => 'Edit Founder',
            'active' => 'founders',
            'founder' => $this->founderModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/founders_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function founders_update() {
        $this->validateForm('founder', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old_founder = $this->founderModel->getById($id);
            $image = $old_founder->image;

            if (!empty($_FILES['image']['name'])) {
                $new_image = Upload::file($_FILES['image'], 'img');
                if ($new_image) {
                    // Upload::delete($old_founder->image, 'img'); // Optional: delete old image
                    $image = $new_image;
                }
            }

            $data = [
                'id' => $id,
                'name' => $_POST['name'],
                'role_id' => $_POST['role_id'],
                'role_en' => Translator::translate($_POST['role_id']),
                'quote_id' => $_POST['quote_id'],
                'quote_en' => Translator::translate($_POST['quote_id']),
                'linkedin_url' => $_POST['linkedin_url'],
                'display_order' => $_POST['display_order'] ?? 0,
                'image' => $image
            ];

            if ($this->founderModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Founder', "Memperbarui founder '{$_POST['name']}'");
                Flasher::setFlash('Founder', 'berhasil diperbarui', 'success');
            } else {
                Flasher::setFlash('Founder', 'gagal diperbarui', 'danger');
            }
            header('Location: ' . BASE_URL . 'admin/founders');
            exit;
        }
    }

    public function founders_delete($id) {
        $founder = $this->founderModel->getById($id);
        if ($this->founderModel->delete($id)) {
            $name = $founder ? $founder->name : 'Unknown';
            $this->activityLogModel->log('DELETE', 'Founder', "Menghapus founder '{$name}'");
            Flasher::setFlash('Founder', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Founder', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/founders');
        exit;
    }

    public function portfolio($action = null, $id = null) {
        if ($action === 'create') return $this->portfolio_create();
        if ($action === 'edit' && $id) return $this->portfolio_edit($id);
        $data = [
            'title' => 'Kelola Portofolio',
            'active' => 'portfolio',
            'portfolios' => $this->portfolioModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/portfolio', $data);
        $this->views('layouts/admin_footer');
    }

    public function portfolio_create() {
        $data = [
            'title' => 'Tambah Portofolio Baru',
            'active' => 'portfolio'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/portfolio_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function portfolio_store() {
        $this->validateForm('portfolio', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cover_image = Upload::file($_FILES['cover_image'], 'img/portfolio');

        // Process Project Logos
        $project_logos = [];
        if (!empty($_FILES['project_logos']['name'][0])) {
            foreach ($_FILES['project_logos']['name'] as $key => $val) {
                if (!empty($val)) {
                    $file_data = [
                        'name' => $_FILES['project_logos']['name'][$key],
                        'type' => $_FILES['project_logos']['type'][$key],
                        'tmp_name' => $_FILES['project_logos']['tmp_name'][$key],
                        'error' => $_FILES['project_logos']['error'][$key],
                        'size' => $_FILES['project_logos']['size'][$key]
                    ];
                    
                    $uploaded_file = Upload::file($file_data, 'img/portfolio');
                    if ($uploaded_file) {
                        $project_logos[] = $uploaded_file;
                    }
                }
            }
        }

            $highlights = $this->collectPortfolioHighlights();
            // Approach section was removed from the site
            $approach_id = $approach_en = '';

            $data = [
                'title_id' => $_POST['title_id'],
                'title_en' => Translator::translate($_POST['title_id']),
                'subtitle_id' => $_POST['subtitle_id'],
                'subtitle_en' => Translator::translate($_POST['subtitle_id']),
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'icon_name' => $_POST['icon_name'],
                'cover_image' => $cover_image ?: '',
                'main_category' => '',
                'home_category' => $_POST['home_category'] ?? NULL,
                'partnership_category' => $_POST['partnership_category'] ?? NULL,
                'gi_category' => $_POST['gi_category'] ?? NULL,
                'partner_type' => $_POST['partner_type'],
                'year_start' => $_POST['year_start'] ?? '',
                'year_end' => $_POST['year_end'] ?? '',
                'show_home' => isset($_POST['show_home']) ? 1 : 0,
                'show_partnership' => isset($_POST['show_partnership']) ? 1 : 0,
                'show_gi' => isset($_POST['show_gi']) ? 1 : 0,
                'client_name' => $_POST['client_name'],
                'tags' => $_POST['tags'] ?? '',
                'video_url' => '',
                'detail_content_id' => $_POST['detail_content_id'] ?? '',
                'detail_content_en' => Translator::translate($_POST['detail_content_id'] ?? ''),
                'targets_id' => $_POST['targets_id'] ?? '',
                'targets_en' => Translator::translate($_POST['targets_id'] ?? ''),
                'metrics_id' => $_POST['metrics_id'] ?? '',
                'metrics_en' => Translator::translate($_POST['metrics_id'] ?? ''),
                'approach_id' => $approach_id,
                'approach_en' => $approach_en,
                'highlights' => json_encode($highlights),
                'project_logos' => json_encode($project_logos)
            ];

            if ($this->portfolioModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Portfolio', "Menambahkan portofolio baru '{$_POST['title_id']}'");
                Flasher::setFlash('Portofolio', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/portfolio');
            } else {
                Flasher::setFlash('Portofolio', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/portfolio');
            }
        }
    }

    public function portfolio_edit($id) {
        $data = [
            'title' => 'Edit Portofolio',
            'active' => 'portfolio',
            'portfolio' => $this->portfolioModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/portfolio_edit', $data);
        $this->views('layouts/admin_footer');
    }

    // Build portfolio highlights (photo or YouTube video) from the admin form rows
    private function collectPortfolioHighlights() {
        $highlights = [];
        $types = $_POST['highlight_types'] ?? [];

        foreach ($types as $i => $type) {
            $caption = trim($_POST['highlight_captions'][$i] ?? '');

            if ($type === 'video') {
                $url = trim($_POST['highlight_videos'][$i] ?? '');
                if ($url !== '' && $this->youtubeId($url)) {
                    $highlights[] = ['type' => 'video', 'video_url' => $url, 'caption' => $caption];
                }
                continue;
            }

            $image = $_POST['highlight_existing_imgs'][$i] ?? '';
            if (!empty($_FILES['highlight_imgs']['name'][$i])) {
                $uploaded = Upload::file([
                    'name' => $_FILES['highlight_imgs']['name'][$i],
                    'type' => $_FILES['highlight_imgs']['type'][$i],
                    'tmp_name' => $_FILES['highlight_imgs']['tmp_name'][$i],
                    'error' => $_FILES['highlight_imgs']['error'][$i],
                    'size' => $_FILES['highlight_imgs']['size'][$i]
                ], 'img/portfolio');
                if ($uploaded) {
                    $image = $uploaded;
                }
            }
            if ($image !== '') {
                $highlights[] = ['type' => 'image', 'image' => $image, 'caption' => $caption];
            }
        }

        return $highlights;
    }

    public function portfolio_update() {
        $this->validateForm('portfolio', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old_portfolio = $this->portfolioModel->getById($id);
            $cover_image = $old_portfolio->cover_image;

            if (!empty($_FILES['cover_image']['name'])) {
                $new_image = Upload::file($_FILES['cover_image'], 'img/portfolio');
                if ($new_image) {
                    Upload::delete($old_portfolio->cover_image, 'img/portfolio');
                    $cover_image = $new_image;
                }
            }

        // Process Project Logos
        $project_logos = [];
        
        // Existing logos
        if (isset($_POST['existing_project_logos'])) {
            foreach ($_POST['existing_project_logos'] as $logo) {
                if (!empty($logo)) {
                    $project_logos[] = $logo;
                }
            }
        }

        // New logos
        if (!empty($_FILES['project_logos']['name'][0])) {
            foreach ($_FILES['project_logos']['name'] as $key => $val) {
                if (!empty($val)) {
                    $file_data = [
                        'name' => $_FILES['project_logos']['name'][$key],
                        'type' => $_FILES['project_logos']['type'][$key],
                        'tmp_name' => $_FILES['project_logos']['tmp_name'][$key],
                        'error' => $_FILES['project_logos']['error'][$key],
                        'size' => $_FILES['project_logos']['size'][$key]
                    ];
                    
                    $uploaded_file = Upload::file($file_data, 'img/portfolio');
                    if ($uploaded_file) {
                        $project_logos[] = $uploaded_file;
                    }
                }
            }
        }

        // Process Highlights
            $highlights = $this->collectPortfolioHighlights();
            // Approach section was removed from the site; keep stored data untouched
            $approach_id = $old_portfolio->approach_id;
            $approach_en = $old_portfolio->approach_en;

            $data = [
                'id' => $id,
                'title_id' => $_POST['title_id'],
                'title_en' => Translator::translate($_POST['title_id']),
                'subtitle_id' => $_POST['subtitle_id'],
                'subtitle_en' => Translator::translate($_POST['subtitle_id']),
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'icon_name' => $_POST['icon_name'],
                'cover_image' => $cover_image,
                'main_category' => $old_portfolio->main_category,
                'home_category' => $_POST['home_category'] ?? $old_portfolio->home_category,
                'partnership_category' => $_POST['partnership_category'] ?? NULL,
                'gi_category' => $_POST['gi_category'] ?? NULL,
                'partner_type' => $_POST['partner_type'],
                'year_start' => $_POST['year_start'] ?? '',
                'year_end' => $_POST['year_end'] ?? '',
                'show_home' => isset($_POST['show_home']) ? 1 : 0,
                'show_partnership' => isset($_POST['show_partnership']) ? 1 : 0,
                'show_gi' => isset($_POST['show_gi']) ? 1 : 0,
                'client_name' => $_POST['client_name'],
                'tags' => $_POST['tags'] ?? '',
                'video_url' => '',
                'detail_content_id' => $_POST['detail_content_id'] ?? '',
                'detail_content_en' => Translator::translate($_POST['detail_content_id'] ?? ''),
                'targets_id' => $_POST['targets_id'] ?? '',
                'targets_en' => Translator::translate($_POST['targets_id'] ?? ''),
                'metrics_id' => $_POST['metrics_id'] ?? '',
                'metrics_en' => Translator::translate($_POST['metrics_id'] ?? ''),
                'approach_id' => $approach_id,
                'approach_en' => $approach_en,
                'highlights' => json_encode($highlights),
                'project_logos' => json_encode($project_logos)
            ];

            if ($this->portfolioModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Portfolio', "Memperbarui portofolio '{$_POST['title_id']}'");
                Flasher::setFlash('Portofolio', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/portfolio');
            } else {
                Flasher::setFlash('Portofolio', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/portfolio');
            }
        }
    }

    public function portfolio_delete($id) {
        $portfolio = $this->portfolioModel->getById($id);
        if ($portfolio) {
            Upload::delete($portfolio->cover_image, 'img/portfolio');
        }

        if ($this->portfolioModel->delete($id)) {
            $name = $portfolio ? $portfolio->title_id : 'Unknown Portfolio';
            $this->activityLogModel->log('DELETE', 'Portfolio', "Menghapus portofolio '{$name}'");
            Flasher::setFlash('Portofolio', 'berhasil dihapus', 'success');
            header('Location: ' . BASE_URL . 'admin/portfolio');
        } else {
            Flasher::setFlash('Portofolio', 'gagal dihapus', 'danger');
            header('Location: ' . BASE_URL . 'admin/portfolio');
        }
    }

    public function articles($action = null, $id = null) {
        if ($action === 'create') return $this->articles_create('blog');
        if ($action === 'edit' && $id) return $this->articles_edit($id);
        $data = [
            'title' => 'Kelola Artikel Blog',
            'active' => 'articles',
            'articles' => $this->articleModel->getByType('blog')
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/articles', $data);
        $this->views('layouts/admin_footer');
    }

    public function library($action = null, $id = null) {
        if ($action === 'create') return $this->articles_create('library');
        if ($action === 'edit' && $id) return $this->articles_edit($id);
        $data = [
            'title' => 'Kelola Library',
            'active' => 'library',
            'articles' => $this->articleModel->getByType('library')
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/articles', $data);
        $this->views('layouts/admin_footer');
    }

    public function articles_create($type = 'blog') {
        $data = [
            'title' => ($type == 'blog' ? 'Tambah Artikel Blog' : 'Tambah Resource Library'), 
            'active' => ($type == 'blog' ? 'articles' : 'library'),
            'type' => $type
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/articles_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function articles_edit($id) {
        $article = $this->articleModel->getById($id);
        $data = [
            'title' => 'Edit Artikel',
            'active' => ($article->type == 'blog' ? 'articles' : 'library'),
            'article' => $article
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/articles_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function articles_update() {
        $this->validateForm('article', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $id = $_POST['id'];


                $old_article = $this->articleModel->getById($id);
                if (!$old_article) {
                    Flasher::setFlash('Artikel', 'tidak ditemukan', 'danger');
                    header('Location: ' . BASE_URL . 'admin/articles');
                    exit;
                }

                $image = $old_article->image;

                if (!empty($_FILES['image']['name'])) {
                    $new_image = Upload::file($_FILES['image'], 'img/blog');
                    if ($new_image) {
                        Upload::delete($old_article->image, 'img/blog');
                        $image = $new_image;
                    } else {
                        Flasher::setFlash('Gambar', 'gagal diunggah (Cek ukuran maks 2MB)', 'warning');
                    }
                }

                // Handle Translation safely
                $title_en = Translator::translate($_POST['title_id']);

                if (strlen($_POST['content_id']) < 3000) {
                    $content_en = Translator::translate($_POST['content_id']);
                } else {
                    $content_en = $_POST['content_id'];
                }

                $data = [
                    'id' => $id,
                    'title_id' => $_POST['title_id'],
                    'title_en' => $title_en,
                    'content_id' => $_POST['content_id'],
                    'content_en' => $content_en,
                    'image' => $image,
                    'category' => $_POST['category'],
                    'tags' => $_POST['tags'],
                    'slug' => str_replace([' ', '/', '\\', '?', '#', '&'], '-', strtolower($title_en)),
                    'author' => $_SESSION['user_name'] ?? 'Admin',
                    'status' => $_POST['status'],
                    'type' => $_POST['type']
                ];

                if ($this->articleModel->update($data)) {
                    $redirect = ($_POST['type'] == 'blog' ? 'admin/articles' : 'admin/library');
                    $type_label = ($data['type'] == 'blog') ? 'Artikel' : 'Pustaka';
                    $this->activityLogModel->log('UPDATE', $type_label, "Memperbarui {$type_label} '{$_POST['title_id']}'");
                    Flasher::setFlash('Artikel', 'berhasil diperbarui', 'success');
                    header('Location: ' . BASE_URL . $redirect);
                    exit;
                } else {
                    throw new Exception("Database update returned false.");
                }
            } catch (Exception $e) {
                $redirect = ($_POST['type'] == 'blog' ? 'admin/articles' : 'admin/library');
                Flasher::setFlash('Gagal memperbarui artikel', $e->getMessage(), 'danger');
                header('Location: ' . BASE_URL . $redirect);
                exit;
            }
        }
    }

    public function articles_store() {
        $this->validateForm('article', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {

                $image = '';
                if (!empty($_FILES['image']['name'])) {
                    $image = Upload::file($_FILES['image'], 'img/blog');
                    if (!$image) {
                        Flasher::setFlash('Gambar', 'gagal diunggah (Cek ukuran maks 2MB)', 'warning');
                    }
                }

                $title_en = Translator::translate($_POST['title_id']);

                if (strlen($_POST['content_id']) < 3000) {
                    $content_en = Translator::translate($_POST['content_id']);
                } else {
                    $content_en = $_POST['content_id'];
                }

                $data = [
                    'title_id' => $_POST['title_id'],
                    'title_en' => $title_en,
                    'content_id' => $_POST['content_id'],
                    'content_en' => $content_en,
                    'image' => $image ?: '',
                    'category' => $_POST['category'],
                    'tags' => $_POST['tags'],
                    'slug' => str_replace([' ', '/', '\\', '?', '#', '&'], '-', strtolower($title_en)),
                    'author' => $_SESSION['user_name'] ?? 'Admin',
                    'status' => $_POST['status'],
                    'type' => $_POST['type']
                ];

                if ($this->articleModel->add($data)) {
                    $redirect = ($_POST['type'] == 'blog' ? 'admin/articles' : 'admin/library');
                    $type_label = ($data['type'] == 'blog') ? 'Artikel' : 'Pustaka';
                    $this->activityLogModel->log('CREATE', $type_label, "Membuat {$type_label} baru '{$_POST['title_id']}'");
                    Flasher::setFlash('Artikel', 'berhasil ditambahkan', 'success');
                    header('Location: ' . BASE_URL . $redirect);
                    exit;
                } else {
                    throw new Exception("Database insert returned false.");
                }
            } catch (Exception $e) {
                $redirect = ($_POST['type'] == 'blog' ? 'admin/articles' : 'admin/library');
                Flasher::setFlash('Gagal menambahkan artikel', $e->getMessage(), 'danger');
                header('Location: ' . BASE_URL . $redirect);
                exit;
            }
        }
    }

    public function articles_delete($id) {
        $article = $this->articleModel->getById($id);
        $type = $article->type ?? 'blog';
        if ($article) {
            Upload::delete($article->image, 'img/blog');
        }

        if ($this->articleModel->delete($id)) {
            $name = $article ? $article->title_id : 'Unknown Article';
            $type_label = ($type == 'blog') ? 'Artikel' : 'Pustaka';
            $this->activityLogModel->log('DELETE', $type_label, "Menghapus {$type_label} '{$name}'");
            Flasher::setFlash('Artikel', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Artikel', 'gagal dihapus', 'danger');
        }
        $redirect = ($type == 'blog' ? 'admin/articles' : 'admin/library');
        header('Location: ' . BASE_URL . $redirect);
        exit;
    }

    public function publications($action = null, $id = null) {
        if ($action === 'create') return $this->publications_create();
        if ($action === 'edit' && $id) return $this->publications_edit($id);
        $data = [
            'title' => 'Kelola Publikasi',
            'active' => 'publications',
            'publications' => $this->publicationModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/publications', $data);
        $this->views('layouts/admin_footer');
    }

    public function publications_create() {
        $data = ['title' => 'Tambah Publikasi Baru', 'active' => 'publications'];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/publications_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function publications_edit($id) {
        $data = [
            'title' => 'Edit Publikasi',
            'active' => 'publications',
            'publication' => $this->publicationModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/publications_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function publications_update() {
        $this->validateForm('publication', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];


            $old = $this->publicationModel->getById($id);
            $thumbnail = $old->thumbnail;
            $file_path = $old->file_path;
            $preview_path = $old->preview_path;

            if (!empty($_FILES['thumbnail']['name'])) {
                $new_thumb = Upload::file($_FILES['thumbnail'], 'img/publications');
                if ($new_thumb) {
                    Upload::delete($old->thumbnail, 'img/publications');
                    $thumbnail = $new_thumb;
                }
            }

            if (!empty($_FILES['file_path']['name'])) {
                $new_file = Upload::file($_FILES['file_path'], 'docs', ['pdf']);
                if ($new_file) {
                    Upload::delete($old->file_path, 'docs');
                    $file_path = $new_file;
                }
            }

            if (!empty($_FILES['preview_path']['name'])) {
                $new_preview = Upload::file($_FILES['preview_path'], 'docs', ['pdf']);
                if ($new_preview) {
                    Upload::delete($old->preview_path, 'docs');
                    $preview_path = $new_preview;
                }
            }

            $data = [
                'id' => $id,
                'title_id' => $_POST['title_id'],
                'title_en' => Translator::translate($_POST['title_id']),
                'type' => $_POST['type'],
                'file_path' => $file_path,
                'preview_path' => $preview_path,
                'thumbnail' => $thumbnail,
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'external_link' => $_POST['external_link'] ?? '',
                'is_paid' => isset($_POST['is_paid']) ? 1 : 0,
                'price' => $_POST['price'] ?? 0
            ];

            if ($this->publicationModel->update($data)) {
                Flasher::setFlash('Publikasi', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/publications');
                exit;
            } else {
                Flasher::setFlash('Publikasi', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/publications');
                exit;
            }
        }
    }

    public function publications_store() {
        $this->validateForm('publication', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $thumbnail = Upload::file($_FILES['thumbnail'], 'img/publications');
            $file_path = Upload::file($_FILES['file_path'], 'docs', ['pdf']);
            $preview_path = Upload::file($_FILES['preview_path'], 'docs', ['pdf']);

            $data = [
                'title_id' => $_POST['title_id'],
                'title_en' => Translator::translate($_POST['title_id']),
                'type' => $_POST['type'],
                'file_path' => $file_path ?: '',
                'preview_path' => $preview_path ?: '',
                'thumbnail' => $thumbnail ?: '',
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'external_link' => $_POST['external_link'] ?? '',
                'is_paid' => isset($_POST['is_paid']) ? 1 : 0,
                'price' => $_POST['price'] ?? 0
            ];

            if ($this->publicationModel->add($data)) {
                Flasher::setFlash('Publikasi', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/publications');
                exit;
            } else {
                Flasher::setFlash('Publikasi', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/publications');
                exit;
            }
        }
    }

    public function publications_delete($id) {
        $pub = $this->publicationModel->getById($id);
        if ($pub) {
            Upload::delete($pub->thumbnail, 'img/publications');
            Upload::delete($pub->file_path, 'docs');
        }

        if ($this->publicationModel->delete($id)) {
            Flasher::setFlash('Publikasi', 'berhasil dihapus', 'success');
            header('Location: ' . BASE_URL . 'admin/publications');
            exit;
        } else {
            Flasher::setFlash('Publikasi', 'gagal dihapus', 'danger');
            header('Location: ' . BASE_URL . 'admin/publications');
            exit;
        }
    }

    public function services($action = null, $id = null) {
        if ($action === 'create') return $this->services_create();
        if ($action === 'edit' && $id) return $this->services_edit($id);
        $data = [
            'title' => 'Kategori Layanan',
            'active' => 'services',
            'services' => $this->serviceModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/services', $data);
        $this->views('layouts/admin_footer');
    }

    public function services_create() {
        $data = ['title' => 'Tambah Layanan Utama', 'active' => 'services'];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/services_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function services_edit($id) {
        $data = [
            'title' => 'Edit Layanan Utama',
            'active' => 'services',
            'service' => $this->serviceModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/services_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function services_store() {
        $this->validateForm('service', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $image = Upload::file($_FILES['image'], 'img/services');

            $data = [
                'name_id' => $_POST['name_id'],
                'name_en' => Translator::translate($_POST['name_id']),
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'image' => $image ?: '',
                'order_priority' => $_POST['order_priority']
            ];

            if ($this->serviceModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Services', "Menambahkan kategori layanan baru '{$_POST['name_id']}'");
                Flasher::setFlash('Kategori Layanan', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/services');
            } else {
                Flasher::setFlash('Kategori Layanan', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/services');
            }
        }
    }

    public function services_update() {
        $this->validateForm('service', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old_data = $this->serviceModel->getById($id);
            

            $image = $old_data->image;
            if (!empty($_FILES['image']['name'])) {
                $new_image = Upload::file($_FILES['image'], 'img/services');
                if ($new_image) {
                    if ($image) {
                        Upload::delete($image, 'img/services');
                    }
                    $image = $new_image;
                }
            }

            $data = [
                'id' => $id,
                'name_id' => $_POST['name_id'],
                'name_en' => Translator::translate($_POST['name_id']),
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'image' => $image,
                'order_priority' => $_POST['order_priority']
            ];

            if ($this->serviceModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Services', "Memperbarui kategori layanan '{$_POST['name_id']}'");
                Flasher::setFlash('Kategori Layanan', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/services');
            } else {
                Flasher::setFlash('Kategori Layanan', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/services');
            }
        }
    }

    public function services_delete($id) {
        $service = $this->serviceModel->getById($id);
        if ($service && $service->image) {
            Upload::delete($service->image, 'img/services');
        }

        if ($this->serviceModel->delete($id)) {
            $name = $service ? $service->name_id : 'Unknown Service';
            $this->activityLogModel->log('DELETE', 'Services', "Menghapus kategori layanan '{$name}'");
            Flasher::setFlash('Kategori Layanan', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Kategori Layanan', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/services');
        exit;
    }

    // --- GRANULAR SERVICE ITEMS (CB, PD, CS) ---

    public function services_cb() {
        return $this->gi_services();
    }

    public function services_pd() {
        $data['title'] = 'Layanan Program Development';
        $data['category'] = 'pd';
        $data['items'] = $this->serviceItemModel->getByCategory('pd');
        $this->views('layouts/admin_header', $data);
        $this->views('admin/service_items', $data);
        $this->views('layouts/admin_footer');
    }

    public function services_cs() {
        $data['title'] = 'Layanan Konsultansi';
        $data['category'] = 'cs';
        $data['items'] = $this->serviceItemModel->getByCategory('cs');
        $this->views('layouts/admin_header', $data);
        $this->views('admin/service_items', $data);
        $this->views('layouts/admin_footer');
    }

    public function service_item_create($category) {
        $data['title'] = 'Tambah Item Layanan';
        $data['category'] = $category;
        $this->views('layouts/admin_header', $data);
        $this->views('admin/service_item_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function service_item_store() {
        $this->validateForm('service_item', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image = Upload::file($_FILES['image'], 'img/services');
            
            $data = $_POST;
            $data['image'] = $image ?: '';
            $data['title_en'] = Translator::translate($_POST['title_id']);
            $data['description_en'] = Translator::translate($_POST['description_id']);
            
            if ($this->serviceItemModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Service Items', "Menambahkan item layanan baru '{$_POST['title_id']}'");
                Flasher::setFlash('Item layanan', 'berhasil ditambahkan', 'success');
            } else {
                Flasher::setFlash('Item layanan', 'gagal ditambahkan', 'danger');
            }
            $category = $_POST['category'];
            header('Location: ' . BASE_URL . 'admin/services_' . $category);
            exit;
        }
    }

    public function service_item_edit($id) {
        $data['item'] = $this->serviceItemModel->getById($id);
        $data['title'] = 'Edit Item Layanan';
        $this->views('layouts/admin_header', $data);
        $this->views('admin/service_item_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function service_item_update() {
        $this->validateForm('service_item', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old = $this->serviceItemModel->getById($id);
            $image = $old->image;

            if (!empty($_FILES['image']['name'])) {
                $new_image = Upload::file($_FILES['image'], 'img/services');
                if ($new_image) {
                    if ($old->image) {
                        Upload::delete($old->image, 'img/services');
                    }
                    $image = $new_image;
                }
            }

            $data = $_POST;
            $data['image'] = $image;
            $data['title_en'] = Translator::translate($_POST['title_id']);
            $data['description_en'] = Translator::translate($_POST['description_id']);

            if ($this->serviceItemModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Service Items', "Memperbarui item layanan '{$_POST['title_id']}'");
                Flasher::setFlash('Item layanan', 'berhasil diperbarui', 'success');
            } else {
                Flasher::setFlash('Item layanan', 'gagal diperbarui', 'danger');
            }
            $category = $_POST['category'];
            header('Location: ' . BASE_URL . 'admin/services_' . $category);
            exit;
        }
    }

    public function service_item_delete($id) {
        $item = $this->serviceItemModel->getById($id);
        $category = $item->category;
        
        if ($item->image) {
            Upload::delete($item->image, 'img/services');
        }

        if ($this->serviceItemModel->delete($id)) {
            $name = $item ? $item->title_id : 'Unknown Item';
            $this->activityLogModel->log('DELETE', 'Service Items', "Menghapus item layanan '{$name}'");
            Flasher::setFlash('Item layanan', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Item layanan', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/services_' . $category);
        exit;
    }

    public function impact($page_target = 'home', $action = null, $id = null) {
        // Handle nested actions if called like admin/impact/home/edit/1
        if ($page_target === 'create') return $this->impact_create();
        if ($page_target === 'edit' && $action) return $this->impact_edit($action);

        $allImpacts = $this->impactModel->getByPage($page_target);
        
        $data = [
            'title' => 'Data Dampak: ' . strtoupper($page_target),
            'active' => 'impact_' . $page_target,
            'page_target' => $page_target,
            'impacts' => $allImpacts
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/impact', $data);
        $this->views('layouts/admin_footer');
    }

    public function impact_create() {
        $data = [
            'title' => 'Tambah Data Dampak',
            'active' => 'impact_' . preg_replace('/[^a-z_]/', '', $_GET['page'] ?? 'home'),
            'selected_page' => $_GET['page'] ?? 'home'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/impact_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function impact_store() {
        $this->validateForm('impact', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $page = $_POST['page'] ?? 'home';
            $data = [
                'label_id' => $_POST['label_id'],
                'label_en' => Translator::translate($_POST['label_id']),
                'value' => $_POST['value'],
                'unit' => $_POST['unit'],
                'icon' => '', // Icon removed as per request
                'page' => $page,
                'section' => $_POST['section'],
                'section_title_id' => $_POST['section_title_id'] ?? '',
                'section_title_en' => ($_POST['section_title_id'] ?? '') ? Translator::translate($_POST['section_title_id']) : '',
                'note_id' => $_POST['note_id'] ?? '',
                'note_en' => ($_POST['note_id'] ?? '') ? Translator::translate($_POST['note_id']) : '',
                'order_num' => $_POST['order_num'] ?? 0
            ];

            if ($this->impactModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Impact', "Menambahkan metrik dampak baru '{$_POST['label_id']}' pada halaman {$page}");
                Flasher::setFlash('Metrik', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/impact/' . $page);
            } else {
                Flasher::setFlash('Metrik', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/impact/' . $page);
            }
            exit;
        }
    }

    public function impact_edit($id) {
        $impact = $this->impactModel->getById($id);
        $data = [
            'title' => 'Edit Data Dampak',
            'active' => 'impact_' . ($impact->page ?? 'home'),
            'impact' => $impact
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/impact_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function impact_update() {
        $this->validateForm('impact', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $page = $_POST['page'] ?? 'home';
            $data = [
                'id' => $_POST['id'],
                'label_id' => $_POST['label_id'],
                'label_en' => Translator::translate($_POST['label_id']),
                'value' => $_POST['value'],
                'unit' => $_POST['unit'],
                'icon' => '', // Icon removed
                'page' => $page,
                'section' => $_POST['section'],
                'section_title_id' => $_POST['section_title_id'] ?? '',
                'section_title_en' => ($_POST['section_title_id'] ?? '') ? Translator::translate($_POST['section_title_id']) : '',
                'note_id' => $_POST['note_id'] ?? '',
                'note_en' => ($_POST['note_id'] ?? '') ? Translator::translate($_POST['note_id']) : '',
                'order_num' => $_POST['order_num'] ?? 0
            ];

            if ($this->impactModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Impact', "Memperbarui metrik dampak '{$_POST['label_id']}' pada halaman {$page}");
                Flasher::setFlash('Metrik', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/impact/' . $page);
            } else {
                Flasher::setFlash('Metrik', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/impact/' . $page);
            }
            exit;
        }
    }

    public function impact_delete($id) {
        $impact = $this->impactModel->getById($id);
        $page = $impact ? $impact->page : 'home';
        
        if ($this->impactModel->delete($id)) {
            $label = $impact ? $impact->label_id : 'Unknown Metric';
            $this->activityLogModel->log('DELETE', 'Impact', "Menghapus metrik dampak '{$label}' dari halaman {$page}");
            Flasher::setFlash('Metrik', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Metrik', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/impact/' . $page);
        exit;
    }

    public function partners($action = null, $id = null) {
        if ($action === 'create') return $this->partners_create();
        if ($action === 'edit' && $id) return $this->partners_edit($id);
        $data = [
            'title' => 'Kelola Partner',
            'active' => 'partners',
            'partners' => $this->partnerModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/partners', $data);
        $this->views('layouts/admin_footer');
    }

    public function partners_create() {
        $data = ['title' => 'Tambah Partner Baru', 'active' => 'partners'];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/partners_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function partners_edit($id) {
        $data = [
            'title' => 'Edit Partner',
            'active' => 'partners',
            'partner' => $this->partnerModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/partners_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function partners_store() {
        $this->validateForm('partner', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $logo = '';
            if (!empty($_FILES['logo']['name'])) {
                $uploadResult = Upload::file($_FILES['logo'], 'img/partners');
                if ($uploadResult) {
                    $logo = $uploadResult;
                }
            }

            $data = [
                'name' => $_POST['name'],
                'logo' => $logo,
                'type' => $_POST['type'],
                'category' => $_POST['category']
            ];

            if ($this->partnerModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Partner', "Menambahkan partner baru '{$_POST['name']}'");
                Flasher::setFlash('Partner', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/partners');
            } else {
                Flasher::setFlash('Partner', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/partners');
            }
        }
    }

    public function partners_update() {
        $this->validateForm('partner', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];


            $old = $this->partnerModel->getById($id);
            $logo = $old->logo;

            if (!empty($_FILES['logo']['name'])) {
                $new_logo = Upload::file($_FILES['logo'], 'img/partners');
                if ($new_logo) {
                    Upload::delete($old->logo, 'img/partners');
                    $logo = $new_logo;
                }
            }

            $data = [
                'id' => $id,
                'name' => $_POST['name'],
                'logo' => $logo,
                'type' => $_POST['type'],
                'category' => $_POST['category']
            ];

            if ($this->partnerModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Partner', "Memperbarui partner '{$_POST['name']}'");
                Flasher::setFlash('Partner', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/partners');
            } else {
                Flasher::setFlash('Partner', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/partners');
            }
        }
    }

    public function partners_delete($id) {
        $partner = $this->partnerModel->getById($id);
        if ($partner) {
            Upload::delete($partner->logo, 'img/partners');
        }

        if ($this->partnerModel->delete($id)) {
            $name = $partner ? $partner->name : 'Unknown Partner';
            $this->activityLogModel->log('DELETE', 'Partner', "Menghapus partner '{$name}'");
            Flasher::setFlash('Partner', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Partner', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/partners');
    }

    /* --- Collaboration Documents --- */

    public function collaboration($action = null, $id = null) {
        $data = [
            'title' => 'Dokumen Kolaborasi',
            'active' => 'collaboration',
            'docs' => $this->collaborationModel->getAllDocuments()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/collaboration/index', $data);
        $this->views('layouts/admin_footer');
    }

    public function collaboration_create() {
        $data = [
            'title' => 'Tambah Dokumen',
            'active' => 'collaboration'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/collaboration/create', $data);
        $this->views('layouts/admin_footer');
    }

    public function collaboration_store() {
        $this->validateForm('collaboration', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $file = $_FILES['document'];
            $file_name = Upload::file($file, 'documents', ['pdf'], realpath(__DIR__ . '/../storage'));

            if ($file_name) {
                $data = [
                    'title_id' => $_POST['title_id'],
                    'title_en' => Translator::translate($_POST['title_id']),
                    'type' => $_POST['type'],
                    'file_path' => $file_name,
                    'status' => $_POST['status'],
                    'auto_send' => isset($_POST['auto_send']) ? 1 : 0
                ];

                if ($this->collaborationModel->addDocument($data)) {
                    $this->activityLogModel->log('CREATE', 'Collaboration', "Menambahkan dokumen kolaborasi baru '{$_POST['title_id']}'");
                    Flasher::setFlash('Dokumen', 'berhasil ditambahkan', 'success');
                    header('Location: ' . BASE_URL . 'admin/collaboration');
                    exit;
                }
            }
            
            Flasher::setFlash('Dokumen', 'gagal ditambahkan', 'danger');
            header('Location: ' . BASE_URL . 'admin/collaboration');
        }
    }

    public function collaboration_edit($id) {
        $data = [
            'title' => 'Edit Dokumen',
            'active' => 'collaboration',
            'doc' => $this->collaborationModel->getDocumentById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/collaboration/edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function collaboration_update() {
        $this->validateForm('collaboration', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old_file = $_POST['old_file'];
            $file = $_FILES['document'];

            if ($file['error'] === 0) {
                $file_name = Upload::file($file, 'documents', ['pdf'], realpath(__DIR__ . '/../storage'));
                if ($file_name) {
                    Upload::delete($old_file, 'documents', realpath(__DIR__ . '/../storage'));
                }
            } else {
                $file_name = $old_file;
            }

            $data = [
                'id' => $id,
                'title_id' => $_POST['title_id'],
                'title_en' => Translator::translate($_POST['title_id']),
                'type' => $_POST['type'],
                'file_path' => $file_name,
                'status' => $_POST['status'],
                    'auto_send' => isset($_POST['auto_send']) ? 1 : 0
            ];

            if ($this->collaborationModel->updateDocument($data)) {
                $this->activityLogModel->log('UPDATE', 'Collaboration', "Memperbarui dokumen kolaborasi '{$_POST['title_id']}'");
                Flasher::setFlash('Dokumen', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/collaboration');
                exit;
            }

            Flasher::setFlash('Dokumen', 'gagal diperbarui', 'danger');
            header('Location: ' . BASE_URL . 'admin/collaboration');
        }
    }

    public function collaboration_delete($id) {
        $doc = $this->collaborationModel->getDocumentById($id);
        if ($doc) {
            Upload::delete($doc->file_path, 'documents', realpath(__DIR__ . '/../storage'));
        }

        if ($this->collaborationModel->deleteDocument($id)) {
            $name = $doc ? $doc->title_id : 'Unknown Document';
            $this->activityLogModel->log('DELETE', 'Collaboration', "Menghapus dokumen kolaborasi '{$name}'");
            Flasher::setFlash('Dokumen', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Dokumen', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/collaboration');
    }


    public function collaboration_requests() {
        $filter = in_array($_GET['status'] ?? '', ['followup', 'sent'], true) ? $_GET['status'] : 'all';
        $data = [
            'title' => 'Permintaan Dokumen',
            'active' => 'collaboration_requests',
            'filter' => $filter,
            'counts' => $this->collaborationModel->countRequests(),
            'requests' => $this->collaborationModel->getAllRequests($filter)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/collaboration/requests', $data);
        $this->views('layouts/admin_footer');
    }

    // Email the requested PDF now (manual follow-up or retry after a failed automatic send)
    public function collaboration_request_send($id) {
        $back = BASE_URL . 'admin/collaboration_requests' . (isset($_GET['status']) ? '?status=' . urlencode($_GET['status']) : '');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . $back); exit; }

        $req = $this->collaborationModel->getRequestById((int) $id);
        $file = $req && $req->doc_file ? realpath(dirname(__DIR__) . '/storage/documents/' . $req->doc_file) : false;
        if (!$req || !$file) {
            Flasher::setFlash('Gagal mengirim.', 'Permintaan atau file dokumennya tidak ditemukan.', 'danger');
            header('Location: ' . $back); exit;
        }

        $mail = Mail::render('doc_user', [
            'nama' => $req->name, 'email' => $req->email, 'instansi' => $req->organization,
            'jabatan' => $req->jabatan, 'dokumen' => $req->doc_title,
        ]);
        if (Mail::send($req->email, $mail['subject'], $mail['html'], $file)) {
            $this->collaborationModel->setDelivery($req->id, 'manual_sent', $_SESSION['user_name'] ?? 'Admin');
            $this->activityLogModel->log('UPDATE', 'Collaboration', "Mengirim dokumen '{$req->doc_title}' ke {$req->email}");
            Flasher::setFlash('Dokumen terkirim', 'ke ' . htmlspecialchars($req->email) . '.', 'success');
        } else {
            Flasher::setFlash('Email gagal dikirim.', 'Periksa pengaturan di Email Settings (klik "Cek Koneksi").', 'danger');
        }
        header('Location: ' . $back);
        exit;
    }

    // Sent through another channel (e.g. personal email): only record it
    public function collaboration_request_mark($id) {
        $back = BASE_URL . 'admin/collaboration_requests' . (isset($_GET['status']) ? '?status=' . urlencode($_GET['status']) : '');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($req = $this->collaborationModel->getRequestById((int) $id))) {
            $this->collaborationModel->setDelivery($req->id, 'manual_sent', $_SESSION['user_name'] ?? 'Admin');
            $this->activityLogModel->log('UPDATE', 'Collaboration', "Menandai dokumen '{$req->doc_title}' untuk {$req->email} sudah dikirim");
            Flasher::setFlash('Permintaan', 'ditandai sudah dikirim.', 'success');
        }
        header('Location: ' . $back);
        exit;
    }

    public function collaboration_request_delete($id) {
        $req = $this->collaborationModel->getRequestById((int) $id);
        if ($req && $this->collaborationModel->deleteRequest($req->id)) {
            $this->activityLogModel->log('DELETE', 'Collaboration', "Menghapus permintaan dokumen dari {$req->email}");
            Flasher::setFlash('Permintaan', 'berhasil dihapus.', 'success');
        }
        header('Location: ' . BASE_URL . 'admin/collaboration_requests');
        exit;
    }

    public function users($action = null, $id = null) {
        if ($_SESSION['user_role'] != 'admin') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.', 'danger');
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        $data = [
            'title' => 'Kelola Akun',
            'active' => 'users',
            'users' => $this->userModel->getAllUsers()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/users', $data);
        $this->views('layouts/admin_footer');
    }

    public function users_create() {
        if ($_SESSION['user_role'] != 'admin') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.', 'danger');
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        $data = [
            'title' => 'Tambah Akun Baru',
            'active' => 'users'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/users_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function users_store() {
        if ($_SESSION['user_role'] != 'admin') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.', 'danger');
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
        $this->validateForm('user', 'store');

        if ($this->userModel->createUser($_POST)) {
            $this->activityLogModel->log('CREATE', 'Users', "Menambahkan pengguna baru '{$_POST['username']}'");
            Flasher::setFlash('Berhasil', 'Akun baru telah ditambahkan.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Terjadi kesalahan saat menambahkan akun.', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/users');
    }

    public function users_edit($id) {
        if ($_SESSION['user_role'] != 'admin') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.', 'danger');
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        $data = [
            'title' => 'Edit Akun',
            'active' => 'users',
            'user' => $this->userModel->getUserById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/users_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function users_update() {
        if ($_SESSION['user_role'] != 'admin') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.', 'danger');
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
        $this->validateForm('user', 'update');

        if ($this->userModel->updateUser($_POST['id'], $_POST)) {
            $this->activityLogModel->log('UPDATE', 'Users', "Memperbarui data pengguna '{$_POST['username']}'");
            Flasher::setFlash('Berhasil', 'Data akun telah diperbarui.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Terjadi kesalahan saat memperbarui akun.', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/users');
    }

    public function users_delete($id) {
        if ($_SESSION['user_role'] != 'admin') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.', 'danger');
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        if ($id == $_SESSION['user_id']) {
            Flasher::setFlash('Gagal', 'Anda tidak dapat menghapus akun sendiri.', 'danger');
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        if ($this->userModel->deleteUser($id)) {
            $this->activityLogModel->log('DELETE', 'Users', "Menghapus pengguna (ID: {$id})");
            Flasher::setFlash('Berhasil', 'Akun telah dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Terjadi kesalahan saat menghapus akun.', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/users');
    }
    public function profile_upload() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
            $user_id = $_SESSION['user_id'];
            $user = $this->userModel->getUserById($user_id);
            $file = $_FILES['photo'];

            $file_name = Upload::file($file, 'profile', ['jpg', 'png', 'jpeg'], realpath(__DIR__ . '/../../public/assets/img'));

            if ($file_name) {
                // Delete old photo if exists
                if ($user->photo) {
                    Upload::delete($user->photo, 'profile', realpath(__DIR__ . '/../../public/assets/img'));
                }

                $data = [
                    'name' => $user->name,
                    'username' => $user->username,
                    'photo' => $file_name
                ];

                if ($this->userModel->updateProfile($user_id, $data)) {
                    // Update session if needed
                    $_SESSION['user_photo'] = $file_name;
                    $this->activityLogModel->log('UPDATE', 'Profile', "Mengganti foto profil");
                    echo json_encode(['status' => 'success', 'photo' => $file_name, 'url' => ASSETS_URL . 'img/profile/' . $file_name]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update database']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Upload failed or invalid file type']);
            }
            exit;
        }
    }

    public function profile_photo_delete() {
        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($user_id);

        if ($user && $user->photo) {
            // Delete physical file
            Upload::delete($user->photo, 'profile', realpath(__DIR__ . '/../../public/assets/img'));

            // Update database
            $data = [
                'name' => $user->name,
                'username' => $user->username,
                'photo' => NULL
            ];

            if ($this->userModel->updateProfile($user_id, $data)) {
                // Update session
                $_SESSION['user_photo'] = NULL;
                $this->activityLogModel->log('UPDATE', 'Profile', "Menghapus foto profil");
                echo json_encode(['status' => 'success', 'message' => 'Foto profil berhasil dihapus']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada foto untuk dihapus']);
        }
        exit;
    }

    // --- TESTIMONIALS ---

    public function testimonials() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
        
        $testimonials = ($page) ? $this->testimonialModel->getByPage($page, false) : $this->testimonialModel->getAll();

        $data = [
            'title' => 'Kelola Testimoni',
            'active' => 'testimonials',
            'testimonials' => $testimonials
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/testimonials', $data);
        $this->views('layouts/admin_footer');
    }

    public function testimonials_create() {
        $data = [
            'title' => 'Tambah Testimoni',
            'active' => 'testimonials'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/testimonials_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function testimonials_store() {
        $this->validateForm('testimonial', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $data['image'] = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $file_name = Upload::file($_FILES['image'], 'testimonials', ['jpg', 'png', 'jpeg'], realpath(__DIR__ . '/../../public/assets/img'));
                if ($file_name) {
                    $data['image'] = $file_name;
                }
            }

            // Auto-translate
            $data['role_en'] = Translator::translate($data['role_id']);
            $data['content_en'] = Translator::translate($data['content_id']);

            if ($this->testimonialModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Testimonials', "Menambahkan testimoni baru dari '{$data['client_name']}'");
                Flasher::setFlash('Testimoni', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/testimonials');
                exit;
            } else {
                Flasher::setFlash('Testimoni', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/testimonials');
                exit;
            }
        }
    }

    public function testimonials_edit($id) {
        $data = [
            'title' => 'Edit Testimoni',
            'active' => 'testimonials',
            'testimonial' => $this->testimonialModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/testimonials_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function testimonials_update() {
        $this->validateForm('testimonial', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $old_data = $this->testimonialModel->getById($data['id']);
            $data['image'] = $old_data->image;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $file_name = Upload::file($_FILES['image'], 'testimonials', ['jpg', 'png', 'jpeg'], realpath(__DIR__ . '/../../public/assets/img'));
                if ($file_name) {
                    if ($old_data->image) {
                        Upload::delete($old_data->image, 'testimonials', realpath(__DIR__ . '/../../public/assets/img'));
                    }
                    $data['image'] = $file_name;
                }
            }

            // Auto-translate
            $data['role_en'] = Translator::translate($data['role_id']);
            $data['content_en'] = Translator::translate($data['content_id']);

            if ($this->testimonialModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Testimonials', "Memperbarui testimoni dari '{$data['client_name']}'");
                Flasher::setFlash('Testimoni', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/testimonials');
                exit;
            } else {
                Flasher::setFlash('Testimoni', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/testimonials');
                exit;
            }
        }
    }

    public function testimonials_delete($id) {
        $testimonial = $this->testimonialModel->getById($id);
        if ($testimonial->image) {
            Upload::delete($testimonial->image, 'testimonials', realpath(__DIR__ . '/../../public/assets/img'));
        }

        if ($this->testimonialModel->delete($id)) {
            Flasher::setFlash('Testimoni', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Testimoni', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/testimonials');
        exit;
    }

    // --- FAQS ---

    public function faqs() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
        $faqs = ($page) ? $this->faqModel->getByPage($page, false) : $this->faqModel->getAll();

        $data = [
            'title' => 'Kelola FAQ',
            'active' => 'faqs',
            'faqs' => $faqs
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/faqs', $data);
        $this->views('layouts/admin_footer');
    }

    public function faqs_create() {
        $data = [
            'title' => 'Tambah FAQ',
            'active' => 'faqs'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/faqs_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function faqs_store() {
        $this->validateForm('faq', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['question_en'] = Translator::translate($_POST['question_id']);
            $_POST['answer_en'] = Translator::translate($_POST['answer_id']);

            if ($this->faqModel->add($_POST)) {
                $this->activityLogModel->log('CREATE', 'FAQ', "Menambahkan FAQ baru '{$_POST['question_id']}'");
                Flasher::setFlash('FAQ', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/faqs');
                exit;
            } else {
                Flasher::setFlash('FAQ', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/faqs');
                exit;
            }
        }
    }

    public function faqs_edit($id) {
        $data = [
            'title' => 'Edit FAQ',
            'active' => 'faqs',
            'faq' => $this->faqModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/faqs_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function faqs_update() {
        $this->validateForm('faq', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['question_en'] = Translator::translate($_POST['question_id']);
            $_POST['answer_en'] = Translator::translate($_POST['answer_id']);

            if ($this->faqModel->update($_POST)) {
                $this->activityLogModel->log('UPDATE', 'FAQ', "Memperbarui FAQ '{$_POST['question_id']}'");
                Flasher::setFlash('FAQ', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/faqs');
                exit;
            } else {
                Flasher::setFlash('FAQ', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/faqs');
                exit;
            }
        }
    }

    public function faqs_delete($id) {
        $faq = $this->faqModel->getById($id);
        if ($this->faqModel->delete($id)) {
            $question = $faq ? $faq->question_id : 'Unknown FAQ';
            $this->activityLogModel->log('DELETE', 'FAQ', "Menghapus FAQ '{$question}'");
            Flasher::setFlash('FAQ', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('FAQ', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/faqs');
        exit;
    }

    // --- GI SERVICES ---

    public function gi_services($action = null, $id = null) {
        if ($action === 'create') return $this->gi_services_create();
        if ($action === 'edit' && $id) return $this->gi_services_edit($id);
        
        $data = [
            'title' => 'Kelola Capacity Building',
            'active' => 'services_cb',
            'services' => $this->giServiceModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/gi_services', $data);
        $this->views('layouts/admin_footer');
    }

    public function gi_services_create() {
        $data = [
            'title' => 'Tambah Layanan GI Baru',
            'active' => 'gi_services'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/gi_services_create', $data);
        $this->views('layouts/admin_footer');
    }

    public function gi_services_store() {
        $this->validateForm('gi_service', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image = Upload::file($_FILES['image'], 'img/gi');
            
            // Process Highlights
            $highlights = [];
            if (!empty($_FILES['highlight_imgs']['name'][0])) {
                foreach ($_FILES['highlight_imgs']['name'] as $key => $val) {
                    if (!empty($val)) {
                        $file_data = [
                            'name' => $_FILES['highlight_imgs']['name'][$key],
                            'type' => $_FILES['highlight_imgs']['type'][$key],
                            'tmp_name' => $_FILES['highlight_imgs']['tmp_name'][$key],
                            'error' => $_FILES['highlight_imgs']['error'][$key],
                            'size' => $_FILES['highlight_imgs']['size'][$key]
                        ];
                        $uploaded_file = Upload::file($file_data, 'img/gi');
                        if ($uploaded_file) {
                            $highlights[] = [
                                'image' => $uploaded_file,
                                'caption' => $_POST['highlight_captions'][$key] ?? ''
                            ];
                        }
                    }
                }
            }

            $data = $_POST;
            $data['image'] = $image ?: '';
            $data['highlights'] = json_encode($highlights);
            
            // Auto-translate
            $data['title_en'] = Translator::translate($data['title_id']);

            $data['description_en'] = Translator::translate($data['description_id']);
            $data['detail_content_en'] = Translator::translate($data['detail_content_id']);
            
            // Handle JSON Translation for Program Points
            if (!empty($data['program_points_id'])) {
                $points_id = json_decode($data['program_points_id'], true);
                if (is_array($points_id)) {
                    $points_en = [];
                    foreach ($points_id as $point) {
                        $points_en[] = [
                            'title' => Translator::translate($point['title'] ?? ''),
                            'desc' => Translator::translate($point['desc'] ?? '')
                        ];
                    }
                    $data['program_points_en'] = json_encode($points_en);
                } else {
                     $data['program_points_en'] = $data['program_points_id']; // Fallback
                }
            }
            
            $data['location_en'] = Translator::translate($data['location_id'] ?: '');
            $data['service_type_en'] = Translator::translate($data['service_type_id'] ?: '');

            $data['slug'] = str_replace(' ', '-', strtolower($data['title_en']));

            // Ensure unique slug
            $base_slug = $data['slug'];
            $counter = 1;
            while ($this->giServiceModel->getBySlug($data['slug'])) {
                $data['slug'] = $base_slug . '-' . $counter;
                $counter++;
            }

            if ($this->giServiceModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'GI Services', "Menambahkan layanan GI baru '{$data['title_id']}'");
                Flasher::setFlash('Layanan GI', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/services_cb');
            } else {
                Flasher::setFlash('Layanan GI', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/services_cb');
            }
        }
    }

    public function gi_services_edit($id) {
        $data = [
            'title' => 'Edit Layanan GI',
            'active' => 'gi_services',
            'service' => $this->giServiceModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/gi_services_edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function gi_services_update() {
        $this->validateForm('gi_service', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old = $this->giServiceModel->getById($id);
            $image = $old->image;

            if (!empty($_FILES['image']['name'])) {
                $new_image = Upload::file($_FILES['image'], 'img/gi');
                if ($new_image) {
                    Upload::delete($old->image, 'img/gi');
                    $image = $new_image;
                }
            }

            // Process Highlights
            $highlights = [];
            
            // Existing highlights
            if (isset($_POST['existing_highlight_imgs'])) {
                foreach ($_POST['existing_highlight_imgs'] as $key => $img) {
                    if (!empty($img)) {
                        $highlights[] = [
                            'image' => $img,
                            'caption' => $_POST['existing_highlight_captions'][$key] ?? ''
                        ];
                    }
                }
            }

            // New highlights
            if (!empty($_FILES['highlight_imgs']['name'][0])) {
                foreach ($_FILES['highlight_imgs']['name'] as $key => $val) {
                    if (!empty($val)) {
                        $file_data = [
                            'name' => $_FILES['highlight_imgs']['name'][$key],
                            'type' => $_FILES['highlight_imgs']['type'][$key],
                            'tmp_name' => $_FILES['highlight_imgs']['tmp_name'][$key],
                            'error' => $_FILES['highlight_imgs']['error'][$key],
                            'size' => $_FILES['highlight_imgs']['size'][$key]
                        ];
                        
                        $uploaded_file = Upload::file($file_data, 'img/gi');
                        if ($uploaded_file) {
                            $highlights[] = [
                                'image' => $uploaded_file,
                                'caption' => $_POST['highlight_captions'][$key] ?? ''
                            ];
                        }
                    }
                }
            }

            $data = $_POST;
            $data['image'] = $image;
            $data['highlights'] = json_encode($highlights);

            // Auto-translate
            $data['title_en'] = Translator::translate($data['title_id']);

            $data['description_en'] = Translator::translate($data['description_id']);
            $data['detail_content_en'] = Translator::translate($data['detail_content_id']);

            // Handle JSON Translation for Program Points
            if (!empty($data['program_points_id'])) {
                $points_id = json_decode($data['program_points_id'], true);
                if (is_array($points_id)) {
                    $points_en = [];
                    foreach ($points_id as $point) {
                        $points_en[] = [
                            'title' => Translator::translate($point['title'] ?? ''),
                            'desc' => Translator::translate($point['desc'] ?? '')
                        ];
                    }
                    $data['program_points_en'] = json_encode($points_en);
                } else {
                     $data['program_points_en'] = $data['program_points_id']; // Fallback
                }
            }

            $data['location_en'] = Translator::translate($data['location_id']);
            $data['service_type_en'] = Translator::translate($data['service_type_id']);

            $data['slug'] = str_replace(' ', '-', strtolower($data['title_en']));

            // Ensure unique slug for update
            $base_slug = $data['slug'];
            $counter = 1;
            $existing = $this->giServiceModel->getBySlug($data['slug']);
            while ($existing && $existing->id != $id) {
                $data['slug'] = $base_slug . '-' . $counter;
                $counter++;
                $existing = $this->giServiceModel->getBySlug($data['slug']);
            }

            if ($this->giServiceModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'GI Services', "Memperbarui layanan GI '{$data['title_id']}'");
                Flasher::setFlash('Layanan GI', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/services_cb');
            } else {
                Flasher::setFlash('Layanan GI', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/services_cb');
            }
        }
    }

    public function gi_services_delete($id) {
        $service = $this->giServiceModel->getById($id);
        if ($service && $service->image) {
            Upload::delete($service->image, 'img/gi');
        }

        if ($this->giServiceModel->delete($id)) {
            $name = $service ? $service->title_id : 'Unknown GI Service';
            $this->activityLogModel->log('DELETE', 'GI Services', "Menghapus layanan GI '{$name}'");
            Flasher::setFlash('Layanan GI', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Layanan GI', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/services_cb');
        exit;
    }

    // --- GI VIDEOS ---

    public function gi_videos() {
        $data = [
            'title' => 'Kelola Video GI',
            'active' => 'gi_videos',
            'videos' => $this->giVideoModel->getAll(),
            'section' => $this->pageSectionModel->getByPageAndSection('gi', 'videos')
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/gi_videos/index', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_gi_video_section() {
        $this->validateForm('gi_video_section', 'update');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/gi_videos');
            exit;
        }

        $titleId = trim($_POST['title_id'] ?? '');
        $subtitleId = trim($_POST['content_id'] ?? '');
        $youtubeUrl = trim($_POST['youtube_url'] ?? '');
        if ($youtubeUrl !== '' && !filter_var($youtubeUrl, FILTER_VALIDATE_URL)) {
            Flasher::setFlash('Link YouTube', 'tidak valid', 'danger');
            header('Location: ' . BASE_URL . 'admin/gi_videos');
            exit;
        }

        $sectionData = [
            'page_name' => 'gi',
            'section_key' => 'videos',
            'title_id' => $titleId,
            'title_en' => $titleId !== '' ? Translator::translate($titleId) : '',
            'content_id' => $subtitleId,
            'content_en' => $subtitleId !== '' ? Translator::translate($subtitleId) : '',
            'content_2_id' => $youtubeUrl,
            'content_2_en' => $youtubeUrl,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if ($this->pageSectionModel->upsert($sectionData)) {
            $this->activityLogModel->log('UPDATE', 'GI Videos', "Memperbarui pengaturan section Belajar Bersama GoSirk");
            Flasher::setFlash('Section Video', 'berhasil diperbarui', 'success');
        } else {
            Flasher::setFlash('Section Video', 'gagal diperbarui', 'danger');
        }

        header('Location: ' . BASE_URL . 'admin/gi_videos');
        exit;
    }

    public function gi_videos_create() {
        $data = [
            'title' => 'Tambah Video GI',
            'active' => 'gi_videos'
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/gi_videos/create', $data);
        $this->views('layouts/admin_footer');
    }

    public function gi_videos_store() {
        $this->validateForm('gi_video', 'store');
        if ($_POST) {
            $data = $_POST;
            $data['thumbnail'] = null;

            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['name']) {
                $upload = Upload::file($_FILES['thumbnail'], 'img/gi/videos');
                if ($upload) {
                    $data['thumbnail'] = $upload;
                }
            }

            // Title/description are no longer shown on the site
            $data['title_id'] = $data['title_en'] = '';
            $data['description_id'] = $data['description_en'] = '';

            if ($this->giVideoModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'GI Videos', "Menambahkan video GI baru '{$data['url']}'");
                Flasher::setFlash('Video GI', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASE_URL . 'admin/gi_videos');
            } else {
                Flasher::setFlash('Video GI', 'gagal ditambahkan', 'danger');
                header('Location: ' . BASE_URL . 'admin/gi_videos');
            }
        }
    }

    public function gi_videos_edit($id) {
        $data = [
            'title' => 'Edit Video GI',
            'active' => 'gi_videos',
            'video' => $this->giVideoModel->getById($id)
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/gi_videos/edit', $data);
        $this->views('layouts/admin_footer');
    }

    public function gi_videos_update() {
        $this->validateForm('gi_video', 'update');
        if ($_POST) {
            $data = $_POST;
            $video = $this->giVideoModel->getById($data['id']);
            $data['thumbnail'] = $video->thumbnail;

            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['name']) {
                if ($video->thumbnail) {
                    Upload::delete($video->thumbnail, 'img/gi/videos');
                }
                $upload = Upload::file($_FILES['thumbnail'], 'img/gi/videos');
                if ($upload) {
                    $data['thumbnail'] = $upload;
                }
            }

            // Keep existing title/description (no longer editable)
            $data['title_id'] = $video->title_id;
            $data['title_en'] = $video->title_en;
            $data['description_id'] = $video->description_id;
            $data['description_en'] = $video->description_en;

            if ($this->giVideoModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'GI Videos', "Memperbarui video GI '{$data['url']}'");
                Flasher::setFlash('Video GI', 'berhasil diperbarui', 'success');
                header('Location: ' . BASE_URL . 'admin/gi_videos');
            } else {
                Flasher::setFlash('Video GI', 'gagal diperbarui', 'danger');
                header('Location: ' . BASE_URL . 'admin/gi_videos');
            }
        }
    }

    public function gi_videos_delete($id) {
        $video = $this->giVideoModel->getById($id);
        if ($video && $video->thumbnail) {
            Upload::delete($video->thumbnail, 'img/gi/videos');
        }

        if ($this->giVideoModel->delete($id)) {
            $name = $video ? ($video->title_id ?: $video->url) : 'Unknown GI Video';
            $this->activityLogModel->log('DELETE', 'GI Videos', "Menghapus video GI '{$name}'");
            Flasher::setFlash('Video GI', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Video GI', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/gi_videos');
        exit;
    }

    // --- CONTACTS ---

    public function contacts() {
        $data = [
            'title' => 'Kotak Masuk Kontak',
            'active' => 'contacts',
            'contacts' => $this->contactModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/contacts', $data);
        $this->views('layouts/admin_footer');
    }

    public function contacts_read($id) {
        $this->contactModel->markAsRead($id);
        $contact = $this->contactModel->getById($id);
        echo json_encode($contact);
        exit;
    }

    public function contacts_delete($id) {
        if ($this->contactModel->delete($id)) {
            Flasher::setFlash('Pesan', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Pesan', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/contacts');
        exit;
    }

    // --- PILOT VILLAGES (Implementasi Partner) ---
    // "Sorotan" section on the Implementasi Partner page (stored in page_sections: partner/highlights)
    public function partner_highlights() {
        $section = $this->pageSectionModel->getByPageAndSection('partner', 'highlights');
        $data = [
            'title' => 'Sorotan Implementasi Partner',
            'active' => 'partner_highlights',
            'section' => $section,
            'items' => json_decode($section->content_id ?? '[]', true) ?: []
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/partner_highlights', $data);
        $this->views('layouts/admin_footer');
    }

    public function partner_highlights_update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/partner_highlights');
            exit;
        }

        $highlights = $this->collectPortfolioHighlights();
        $json = json_encode($highlights);
        $saved = $this->pageSectionModel->upsert([
            'page_name' => 'partner',
            'section_key' => 'highlights',
            'content_id' => $json,
            'content_en' => $json,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ]);

        if ($saved) {
            $this->activityLogModel->log('UPDATE', 'Page Section', 'Memperbarui sorotan halaman Implementasi Partner (' . count($highlights) . ' item)');
            Flasher::setFlash('Sorotan', 'berhasil disimpan', 'success');
        } else {
            Flasher::setFlash('Sorotan', 'gagal disimpan', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/partner_highlights');
        exit;
    }

    public function pilot_villages() {
        $data = [
            'title' => 'Kelola Desa Pilot',
            'active' => 'pilot_villages',
            'villages' => $this->pilotVillageModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/pilot_villages', $data);
        $this->views('layouts/admin_footer');
    }

    public function pilot_villages_store() {
        $this->validateForm('pilot_village', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image = '';
            if (!empty($_FILES['image']['name'])) {
                $image = Upload::file($_FILES['image'], 'img');
            }

            $data = [
                'name_id' => $_POST['name_id'],
                'name_en' => Translator::translate($_POST['name_id']),
                'image' => $image,
                'order_priority' => $_POST['order_priority'] ?: 0
            ];

            if ($this->pilotVillageModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Pilot Village', "Menambahkan desa pilot baru '{$_POST['name_id']}'");
                Flasher::setFlash('Desa Pilot', 'berhasil ditambahkan', 'success');
            } else {
                Flasher::setFlash('Desa Pilot', 'gagal ditambahkan', 'danger');
            }
            header('Location: ' . BASE_URL . 'admin/pilot_villages');
            exit;
        }
    }

    public function pilot_villages_update() {
        $this->validateForm('pilot_village', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old = $this->pilotVillageModel->getById($id);
            $image = '';

            if (!empty($_FILES['image']['name'])) {
                $image = Upload::file($_FILES['image'], 'img');
                if ($image && $old->image) {
                    Upload::delete($old->image, 'img');
                }
            }

            $data = [
                'id' => $id,
                'name_id' => $_POST['name_id'],
                'name_en' => Translator::translate($_POST['name_id']),
                'image' => $image,
                'order_priority' => $_POST['order_priority'] ?: 0
            ];

            if ($this->pilotVillageModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Pilot Village', "Memperbarui desa pilot '{$_POST['name_id']}'");
                Flasher::setFlash('Desa Pilot', 'berhasil diperbarui', 'success');
            } else {
                Flasher::setFlash('Desa Pilot', 'gagal diperbarui', 'danger');
            }
            header('Location: ' . BASE_URL . 'admin/pilot_villages');
            exit;
        }
    }

    public function pilot_villages_delete($id) {
        $old = $this->pilotVillageModel->getById($id);
        if ($old && $old->image) {
            Upload::delete($old->image, 'img');
        }
        if ($this->pilotVillageModel->delete($id)) {
            $this->activityLogModel->log('DELETE', 'Pilot Village', "Menghapus desa pilot '{$old->name_id}'");
            Flasher::setFlash('Desa Pilot', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Desa Pilot', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/pilot_villages');
        exit;
    }

    // --- GGC ACTIONS (Aksi Kami) ---
    public function ggc_actions() {
        $data = [
            'title' => 'Kelola Aksi GGC',
            'active' => 'ggc_actions',
            'actions' => $this->ggcActionModel->getAll()
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/ggc_actions', $data);
        $this->views('layouts/admin_footer');
    }

    public function ggc_actions_store() {
        $this->validateForm('ggc_action', 'store');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image = '';
            if (!empty($_FILES['image']['name'])) {
                $image = Upload::file($_FILES['image'], 'img');
            }

            $data = [
                'title_id' => $_POST['title_id'],
                'title_en' => Translator::translate($_POST['title_id']),
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'image' => $image,
                'order_priority' => $_POST['order_priority'] ?: 0
            ];

            if ($this->ggcActionModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'GGC Action', "Menambahkan aksi GGC baru '{$_POST['title_id']}'");
                Flasher::setFlash('Aksi GGC', 'berhasil ditambahkan', 'success');
            } else {
                Flasher::setFlash('Aksi GGC', 'gagal ditambahkan', 'danger');
            }
            header('Location: ' . BASE_URL . 'admin/ggc_actions');
            exit;
        }
    }

    public function ggc_actions_update() {
        $this->validateForm('ggc_action', 'update');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $old = $this->ggcActionModel->getById($id);
            $image = '';

            if (!empty($_FILES['image']['name'])) {
                $image = Upload::file($_FILES['image'], 'img');
                if ($image && $old->image && !filter_var($old->image, FILTER_VALIDATE_URL)) {
                    Upload::delete($old->image, 'img');
                }
            }

            $data = [
                'id' => $id,
                'title_id' => $_POST['title_id'],
                'title_en' => Translator::translate($_POST['title_id']),
                'description_id' => $_POST['description_id'],
                'description_en' => Translator::translate($_POST['description_id']),
                'image' => $image,
                'order_priority' => $_POST['order_priority'] ?: 0
            ];

            if ($this->ggcActionModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'GGC Action', "Memperbarui aksi GGC '{$_POST['title_id']}'");
                Flasher::setFlash('Aksi GGC', 'berhasil diperbarui', 'success');
            } else {
                Flasher::setFlash('Aksi GGC', 'gagal diperbarui', 'danger');
            }
            header('Location: ' . BASE_URL . 'admin/ggc_actions');
            exit;
        }
    }

    public function ggc_actions_delete($id) {
        $old = $this->ggcActionModel->getById($id);
        if ($old && $old->image && !filter_var($old->image, FILTER_VALIDATE_URL)) {
            Upload::delete($old->image, 'img');
        }
        if ($this->ggcActionModel->delete($id)) {
            $this->activityLogModel->log('DELETE', 'GGC Action', "Menghapus aksi GGC '{$old->title_id}'");
            Flasher::setFlash('Aksi GGC', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Aksi GGC', 'gagal dihapus', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/ggc_actions');
        exit;
    }
    // "Program Utama" on the Go Ngompos Project page
    public function gnp_programs() {
        $data = [
            'title' => 'Program Go Ngompos',
            'active' => 'gnp_programs',
            'programs' => $this->gnpProgramModel->getAll(),
            'section' => $this->pageSectionModel->getByPageAndSection('go_ngompos_project', 'programs')
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/gnp_programs', $data);
        $this->views('layouts/admin_footer');
    }

    private function gnpProgramData($old = null) {
        $image = $old->image ?? null;
        if (!empty($_FILES['image']['name'])) {
            $uploaded = Upload::file($_FILES['image'], 'img/gnp', ['jpg', 'jpeg', 'png', 'webp']);
            if ($uploaded) {
                if ($old && $old->image && !preg_match('#^https?://#i', $old->image)) {
                    Upload::delete($old->image, 'img/gnp');
                }
                $image = $uploaded;
            }
        }
        $badge = trim($_POST['badge_id'] ?? '');
        return [
            'badge_id' => $badge,
            'badge_en' => $badge !== '' ? Translator::translate($badge) : '',
            'badge_color' => $_POST['badge_color'] ?? 'success',
            'title_id' => trim($_POST['title_id']),
            'title_en' => Translator::translate(trim($_POST['title_id'])),
            'description_id' => trim($_POST['description_id']),
            'description_en' => Translator::translate(trim($_POST['description_id'])),
            'image' => $image,
            'order_priority' => $_POST['order_priority'] ?? 0,
        ];
    }

    public function gnp_programs_store() {
        $this->validateForm('gnp_program', 'store');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->gnpProgramData();
            if ($data['image'] && $this->gnpProgramModel->add($data)) {
                $this->activityLogModel->log('CREATE', 'Go Ngompos', "Menambahkan program '{$data['title_id']}'");
                Flasher::setFlash('Program', 'berhasil ditambahkan', 'success');
            } else {
                Flasher::setFlash('Program', 'gagal ditambahkan', 'danger');
            }
        }
        header('Location: ' . BASE_URL . 'admin/gnp_programs');
        exit;
    }

    public function gnp_programs_update() {
        $this->validateForm('gnp_program', 'update');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($old = $this->gnpProgramModel->getById((int) $_POST['id']))) {
            $data = $this->gnpProgramData($old);
            $data['id'] = $old->id;
            if ($this->gnpProgramModel->update($data)) {
                $this->activityLogModel->log('UPDATE', 'Go Ngompos', "Memperbarui program '{$data['title_id']}'");
                Flasher::setFlash('Program', 'berhasil diperbarui', 'success');
            } else {
                Flasher::setFlash('Program', 'gagal diperbarui', 'danger');
            }
        }
        header('Location: ' . BASE_URL . 'admin/gnp_programs');
        exit;
    }

    public function gnp_programs_delete($id) {
        $old = $this->gnpProgramModel->getById((int) $id);
        if ($old && $this->gnpProgramModel->delete($old->id)) {
            if ($old->image && !preg_match('#^https?://#i', $old->image)) {
                Upload::delete($old->image, 'img/gnp');
            }
            $this->activityLogModel->log('DELETE', 'Go Ngompos', "Menghapus program '{$old->title_id}'");
            Flasher::setFlash('Program', 'berhasil dihapus', 'success');
        }
        header('Location: ' . BASE_URL . 'admin/gnp_programs');
        exit;
    }

    public function gnp_programs_section() {
        $this->validateForm('gnp_program_section', 'update');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title_id'] ?? '');
            $subtitle = trim($_POST['content_id'] ?? '');
            $this->pageSectionModel->upsert([
                'page_name' => 'go_ngompos_project',
                'section_key' => 'programs',
                'title_id' => $title,
                'title_en' => $title !== '' ? Translator::translate($title) : '',
                'content_id' => $subtitle,
                'content_en' => $subtitle !== '' ? Translator::translate($subtitle) : '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ]);
            $this->activityLogModel->log('UPDATE', 'Go Ngompos', 'Memperbarui pengaturan section Program Utama');
            Flasher::setFlash('Pengaturan section', 'berhasil disimpan', 'success');
        }
        header('Location: ' . BASE_URL . 'admin/gnp_programs');
        exit;
    }

    // Public pages whose texts can be edited (view folder => label)
    const TEXT_PAGES = [
        'layouts' => 'Navigasi & Footer (semua halaman)',
        'home' => 'Home',
        'about' => 'About Us',
        'gi' => 'GoSirk Institute',
        'ggc' => 'GoSirk Green Community',
        'go_ngompos_project' => 'Go Ngompos Project',
        'implentasi_partner' => 'Implementasi Partner',
        'konsultan' => 'Konsultansi',
        'partnership' => 'Partnership',
        'collaboration' => 'Collaboration',
        'contact' => 'Contact',
        'blog' => 'Blog',
        'library' => 'Library',
        'publication' => 'Publikasi',
        'portfolio' => 'Detail Portfolio',
    ];

    // Text keys used by each public page, found by scanning the views for data-i18n attributes
    private function pageTextKeys() {
        $viewsDir = dirname(__DIR__) . '/views/';
        $result = [];
        $seen = [];
        foreach (self::TEXT_PAGES as $folder => $label) {
            $keys = [];
            $files = glob($viewsDir . $folder . '/*.php');
            usort($files, fn($a, $b) => (basename($b) === 'index.php') <=> (basename($a) === 'index.php')); // main page first
            if ($folder === 'home') $files = array_merge($files, glob($viewsDir . 'partials/*.php')); // shared sections first appear on Home
            foreach ($files as $file) {
                if ($folder === 'layouts' && !in_array(basename($file), ['header.php', 'footer.php'], true)) continue;
                preg_match_all('/data-i18n(?:-placeholder)?="([a-z0-9_]+(?:\.[a-z0-9_]+)+)"/i', file_get_contents($file), $m);
                foreach ($m[1] as $key) {
                    if (isset($seen[$key])) continue; // shared keys are listed once, on the first page that uses them
                    $seen[$key] = true;
                    $keys[] = $key;
                }
            }
            if ($folder === 'blog') {
                // Category labels are printed from PHP, so the scan above cannot see them
                if (!class_exists('Article_model')) require_once dirname(__DIR__) . '/models/Article_model.php';
                foreach (Article_model::CATEGORIES as $catKey) {
                    if (!isset($seen[$catKey])) { $seen[$catKey] = true; $keys[] = $catKey; }
                }
            }
            if ($keys) $result[$folder] = ['label' => $label, 'keys' => $keys];
        }
        return $result;
    }

    public function page_texts() {
        $pages = $this->pageTextKeys();
        $page = $this->onlyPage(array_keys($pages), 'page_texts');
        $data = [
            'title' => 'Teks Halaman',
            'active' => 'page_texts',
            'pages' => $pages,
            'page' => $page,
            'overrides' => $this->pageTextModel->getAll(),
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/page_texts', $data);
        $this->views('layouts/admin_footer');
    }

    public function page_texts_update() {
        $page = preg_replace('/[^a-z_]/', '', $_POST['page'] ?? '');
        $back = BASE_URL . 'admin/page_texts?page=' . urlencode($page);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . $back); exit; }

        $allowed = array_flip($this->pageTextKeys()[$page]['keys'] ?? []);
        $changed = 0;
        foreach ((array) ($_POST['texts'] ?? []) as $key => $v) {
            if (!isset($allowed[$key])) continue; // only keys that exist on this page
            $id = trim((string) ($v['id'] ?? ''));
            $en = trim((string) ($v['en'] ?? ''));
            if (!empty($v['reset']) || ($id === '' && $en === '')) {
                $this->pageTextModel->delete($key);
            } else {
                $this->pageTextModel->save($key, $id !== '' ? $id : null, $en !== '' ? $en : null);
            }
            $changed++;
        }
        $label = self::TEXT_PAGES[$page] ?? $page;
        $this->activityLogModel->log('UPDATE', 'Teks Halaman', "Memperbarui {$changed} teks di halaman {$label}");
        Flasher::setFlash('Teks halaman', "berhasil disimpan ({$changed} teks diperbarui).", 'success');
        header('Location: ' . $back);
        exit;
    }

    // Fixed images/backgrounds on public pages (slots defined in PageImages::SLOTS)
    public function page_images() {
        $data = [
            'title' => 'Gambar Halaman',
            'active' => 'page_images',
            'only' => $this->onlyPage(array_keys(PageImages::PAGES), 'page_images'),
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/page_images', $data);
        $this->views('layouts/admin_footer');
    }

    public function page_images_update() {
        $key = $_POST['slot'] ?? '';
        $returnPage = isset(PageImages::PAGES[$_POST['return_page'] ?? '']) ? $_POST['return_page'] : 'home';
        $back = BASE_URL . 'admin/page_images?page=' . rawurlencode($returnPage) . '#slot-' . rawurlencode($key);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset(PageImages::SLOTS[$key])) { header('Location: ' . BASE_URL . 'admin/page_images'); exit; }
        [$page, $label] = PageImages::SLOTS[$key];

        if (!empty($_POST['reset'])) {
            $old = PageImages::custom($key);
            $this->settingModel->update('img_slot.' . $key, '');
            if ($old) Upload::delete($old, PageImages::FOLDER);
            $this->activityLogModel->log('UPDATE', 'Gambar Halaman', "Mengembalikan gambar bawaan: {$page} - {$label}");
            Flasher::setFlash('Gambar', 'dikembalikan ke bawaan.', 'success');
            header('Location: ' . $back); exit;
        }

        if (($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            Flasher::setFlash('Pilih gambar', 'terlebih dahulu.', 'danger');
            header('Location: ' . $back); exit;
        }
        $file = Upload::file($_FILES['image'], PageImages::FOLDER, ['jpg', 'jpeg', 'png', 'webp']);
        if ($file) {
            $old = PageImages::custom($key);
            $this->settingModel->update('img_slot.' . $key, $file);
            if ($old) Upload::delete($old, PageImages::FOLDER);
            $this->activityLogModel->log('UPDATE', 'Gambar Halaman', "Mengganti gambar: {$page} - {$label}");
            Flasher::setFlash('Gambar', 'berhasil diganti.', 'success');
        } else {
            Flasher::setFlash('Gambar', 'gagal diupload.', 'danger');
        }
        header('Location: ' . $back);
        exit;
    }

    // Privacy policy page (/privacy): one rich-text document per language
    public function privacy() {
        $data = [
            'title' => 'Kebijakan Privasi',
            'active' => 'privacy',
            'content_id' => PrivacyPolicy::content('id'),
            'content_en' => PrivacyPolicy::content('en'),
            'updated_at' => PrivacyPolicy::updatedAt(),
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/privacy', $data);
        $this->views('layouts/admin_footer');
    }

    public function privacy_update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['content_id'] ?? '');
            $en = trim($_POST['content_en'] ?? '');
            if (trim(strip_tags($id)) === '' || trim(strip_tags($en)) === '') {
                Flasher::setFlash('Kebijakan Privasi belum disimpan.', 'Isi versi Indonesia dan Inggris tidak boleh kosong.', 'danger');
            } else {
                PrivacyPolicy::save($id, $en);
                $this->activityLogModel->log('UPDATE', 'Kebijakan Privasi', 'Memperbarui Kebijakan Privasi');
                Flasher::setFlash('Kebijakan Privasi', 'berhasil disimpan.', 'success');
            }
        }
        header('Location: ' . BASE_URL . 'admin/privacy');
        exit;
    }

    // Per-page <title> and meta description (settings "seo.<page>.title|description")
    const SEO_PAGES = [
        'home' => 'Home', 'about' => 'About Us', 'gi' => 'GoSirk Institute', 'ggc' => 'GoSirk Green Community',
        'go_ngompos_project' => 'Go Ngompos Project', 'implementasi_partner' => 'Implementasi Partner',
        'konsultan' => 'Konsultansi', 'partnership' => 'Partnership', 'collaboration' => 'Collaboration',
        'contact' => 'Contact', 'blog' => 'Blog', 'library' => 'Library', 'publication' => 'Publikasi',
        'privacy' => 'Kebijakan Privasi',
    ];

    public function seo() {
        $data = [
            'title' => 'SEO Halaman',
            'active' => 'seo',
            'only' => $this->onlyPage(array_keys(self::SEO_PAGES), 'seo'),
            'pages' => self::SEO_PAGES,
            'settings' => $this->settingModel->getAll(),
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/seo', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_seo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $values = [];
            foreach (self::SEO_PAGES as $page => $label) {
                if (!isset($_POST['seo'][$page])) continue; // only the page(s) on the submitted form
                $title = trim($_POST['seo'][$page]['title'] ?? '');
                $desc = trim($_POST['seo'][$page]['description'] ?? '');
                if (mb_strlen($title) > 100) $errors[$page][] = "$label: judul maksimal 100 karakter.";
                if (mb_strlen($desc) > 300) $errors[$page][] = "$label: deskripsi maksimal 300 karakter.";
                $values["seo.$page.title"] = $title;
                $values["seo.$page.description"] = $desc;
            }
            if ($errors) {
                Flasher::keepOldInput([]);
                Flasher::setFlash('SEO belum disimpan.', 'Periksa isian berikut:', 'danger', $errors);
            } else {
                $this->settingModel->updateMultiple($values);
                $this->activityLogModel->log('UPDATE', 'SEO', 'Memperbarui SEO halaman');
                Flasher::setFlash('SEO halaman', 'berhasil disimpan.', 'success');
            }
        }
        $posted = array_keys($_POST['seo'] ?? []);
        header('Location: ' . BASE_URL . 'admin/seo' . (count($posted) === 1 && isset(self::SEO_PAGES[$posted[0]]) ? '?page=' . rawurlencode($posted[0]) : ''));
        exit;
    }

    // Email (Brevo) settings: stored in `settings`, override .env / config.php
    public function email_settings() {
        $config = Mail::config();
        $key = $config['api_key'];
        $data = [
            'title' => 'Pengaturan Email',
            'active' => 'email_settings',
            'config' => $config,
            'key_hint' => $key !== '' ? substr($key, 0, 8) . str_repeat('•', 8) . substr($key, -4) : '',
            'key_source' => trim((string) $this->settingModel->getByKey('mail_brevo_api_key')) !== '' ? 'admin' : ($key !== '' ? 'env' : 'none'),
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/email_settings', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_email_settings() {
        $this->validateForm('email_settings', 'update');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/email_settings');
            exit;
        }

        $values = [
            'mail_from' => trim($_POST['mail_from']),
            'mail_from_name' => trim($_POST['mail_from_name']),
            'mail_admin_address' => trim($_POST['mail_admin_address']),
        ];
        // Empty key field = keep the current key
        if (trim($_POST['mail_brevo_api_key'] ?? '') !== '') {
            $values['mail_brevo_api_key'] = trim($_POST['mail_brevo_api_key']);
        }
        $this->settingModel->updateMultiple($values);
        Mail::resetConfig();

        $this->activityLogModel->log('UPDATE', 'System', 'Memperbarui pengaturan email' . (isset($values['mail_brevo_api_key']) ? ' (termasuk API key)' : ''));
        Flasher::setFlash('Pengaturan email', 'berhasil disimpan. Gunakan "Cek Koneksi" untuk memastikan Brevo menerima pengaturan ini.', 'success');
        header('Location: ' . BASE_URL . 'admin/email_settings');
        exit;
    }

    public function update_email_templates() {
        $this->validateForm('email_templates', 'update');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/email_settings');
            exit;
        }
        $values = [];
        foreach (array_keys(Mail::TEMPLATES) as $key) {
            foreach (['subject', 'body'] as $part) {
                $name = "mail_tpl_{$key}_{$part}";
                $value = trim(str_replace("\r\n", "\n", $_POST[$name] ?? ''));
                // Saving the default text unchanged keeps following future defaults
                $values[$name] = $value === Mail::TEMPLATES[$key][$part] ? '' : $value;
            }
        }
        $this->settingModel->updateMultiple($values);
        $this->activityLogModel->log('UPDATE', 'System', 'Memperbarui template email');
        Flasher::setFlash('Template email', 'berhasil disimpan.', 'success');
        header('Location: ' . BASE_URL . 'admin/email_settings#templates');
        exit;
    }

    public function email_settings_check() {
        $result = Mail::checkConnection();
        $errors = ['brevo' => $result['messages']];
        Flasher::setFlash('Cek koneksi Brevo:', $result['ok'] ? 'semua siap.' : 'ada yang perlu diperbaiki.', $result['ok'] ? 'success' : 'danger', $errors);
        header('Location: ' . BASE_URL . 'admin/email_settings');
        exit;
    }

    public function email_settings_test() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/email_settings');
            exit;
        }
        $to = Mail::config()['admin'];
        $ok = Mail::send($to, 'Email Tes - ' . SITE_NAME, '<p>Ini email tes dari halaman <b>Email Settings</b> admin GoSirk.</p><p>Jika Anda menerima email ini, pengiriman email sudah berfungsi.</p>');
        if ($ok) {
            Flasher::setFlash('Email tes', 'terkirim ke ' . htmlspecialchars($to) . '. Cek inbox (dan folder spam).', 'success');
        } else {
            Flasher::setFlash('Email tes gagal dikirim.', 'Klik "Cek Koneksi" untuk melihat penyebabnya.', 'danger');
        }
        header('Location: ' . BASE_URL . 'admin/email_settings');
        exit;
    }

    public function maintenance() {
        $data = [
            'title' => 'Mode Pemeliharaan',
            'active' => 'maintenance',
            'is_maintenance' => $this->settingModel->getByKey('is_maintenance')
        ];
        $this->views('layouts/admin_header', $data);
        $this->views('admin/maintenance', $data);
        $this->views('layouts/admin_footer');
    }

    public function update_maintenance() {
        $status = isset($_POST['is_maintenance']) ? '1' : '0';
        $this->settingModel->update('is_maintenance', $status);
        
        $msg = ($status == '1') ? 'diaktifkan' : 'dinonaktifkan';
        $this->activityLogModel->log('UPDATE', 'System', "Mode Maintenance {$msg}");
        Flasher::setFlash('Maintenance Mode', 'berhasil ' . $msg, 'success');
        
        header('Location: ' . BASE_URL . 'admin/maintenance');
        exit;
    }
}
