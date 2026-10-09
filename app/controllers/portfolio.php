<?php

class Portfolio extends Controller {
    private $portfolioModel;

    public function __construct() {
        $this->portfolioModel = $this->model('Portfolio_model');
    }

    // All portfolios shown on Home, in the order set for Home, 12 per page (?page=2, ...)
    public function index() {
        $perPage = 12;
        $all = $this->portfolioModel->getForLocation('home');
        $pages = max(1, (int) ceil(count($all) / $perPage));
        $page = min($pages, max(1, (int) ($_GET['page'] ?? 1)));

        $data = [
            'title' => 'Portofolio',
            'meta_description' => 'Portofolio kerja sama GoSirk: proyek dan kolaborasi pengelolaan sampah berkelanjutan bersama pemerintah, komunitas, dan mitra.',
            'portfolios' => array_slice($all, ($page - 1) * $perPage, $perPage),
            'total' => count($all),
            'page' => $page,
            'pages' => $pages
        ];

        $this->views('layouts/header', $data);
        $this->views('portfolio/index', $data);
        $this->views('layouts/footer');
    }

    public function detail($id = null) {
        $portfolio = $id ? $this->portfolioModel->getById($id) : null;
        if (!$portfolio) $this->notFound();

        $data = [
            'title' => $portfolio->title_id,
            'meta_description' => mb_substr(trim(strip_tags($portfolio->description_id ?: ($portfolio->subtitle_id ?? ''))), 0, 160),
            'portfolio' => $portfolio,
            'partner_type' => $this->model('PartnerType_model')->map()[strtolower((string) $portfolio->partner_type)] ?? null
        ];

        $this->views('layouts/header', $data);
        $this->views('portfolio/detail', $data);
        $this->views('layouts/footer');
    }
}
