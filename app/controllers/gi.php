<?php

class gi extends Controller {
    public function index() {
        $portfolioModel = $this->model('Portfolio_model');
        $data = [
            'hero' => $this->model('Hero_model')->getByPage('gi'),
            'portfolios' => $portfolioModel->getAll(),
            'impacts' => $this->model('Impact_model')->getByPage('gi'),
            'articles' => $this->model('Article_model')->getByType('library'),
            'services' => $this->model('GiService_model')->getAll(),
            'testimonials' => $this->model('Testimonial_model')->getByPage('gi'),
            'faqs' => $this->model('Faq_model')->getByPage('gi'),
            'videos' => $this->model('GiVideo_model')->getAll(),
            'hero_transition' => $this->model('Setting_model')->getByKey('gi_hero_transition') ?: 'slide',
            'about_section' => $this->model('PageSection_model')->getByPageAndSection('gi', 'about'),
            'video_section' => $this->model('PageSection_model')->getByPageAndSection('gi', 'videos')
        ];
        $this->views('layouts/header', $data);
        $this->views('gi/index', $data);
        $this->views('layouts/footer');
    }

    public function detail($slug = '') {
        $service = $this->model('GiService_model')->getBySlug($slug);
        if (!$service) {
            header('Location: ' . BASE_URL . 'gi');
            exit;
        }
        $data = [
            'service' => $service,
            'title' => $service->title_id,
            'meta_description' => mb_substr(trim(strip_tags($service->description_id ?? '')), 0, 160)
        ];
        $this->views('layouts/header', $data);
        $this->views('gi/detail', $data);
        $this->views('layouts/footer');
    }

    public function portfolio($id = '') {
        $data['portfolio'] = $this->model('Portfolio_model')->getById($id);
        
        $this->views('layouts/header');
        $this->views('gi/portfolio', $data);
        $this->views('layouts/footer');
    }
}

