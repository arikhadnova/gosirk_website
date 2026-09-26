<?php

class Portfolio extends Controller {
    private $portfolioModel;

    public function __construct() {
        $this->portfolioModel = $this->model('Portfolio_model');
    }

    public function index() {
        header('Location: ' . BASE_URL);
        exit;
    }

    public function detail($id = null) {
        $portfolio = $id ? $this->portfolioModel->getById($id) : null;
        if (!$portfolio) $this->notFound();

        $data = [
            'title' => $portfolio->title_id,
            'meta_description' => mb_substr(trim(strip_tags($portfolio->description_id ?: ($portfolio->subtitle_id ?? ''))), 0, 160),
            'portfolio' => $portfolio
        ];

        $this->views('layouts/header', $data);
        $this->views('portfolio/detail', $data);
        $this->views('layouts/footer');
    }
}
