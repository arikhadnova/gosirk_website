<?php

class konsultan extends Controller {
    public function index() {
        $data = [
            'hero' => $this->model('Hero_model')->getByPage('konsultan'),
            'impacts' => $this->model('Impact_model')->getByPage('konsultan'),
            'testimonials' => $this->model('Testimonial_model')->getByPage('konsultan'),
            'faqs' => $this->model('Faq_model')->getByPage('konsultan'),
            'portfolio_articles' => $this->model('Article_model')->getByCategory('Consulting', 'blog'),
            'hero_transition' => $this->model('Setting_model')->getByKey('konsultan_hero_transition') ?: 'slide',
            'about_section' => $this->model('PageSection_model')->getByPageAndSection('konsultan', 'about')
        ];
        $this->views('layouts/header', $data);
        $this->views('konsultan/index', $data);
        $this->views('layouts/footer');
    }

    public function detail($id = '') {
        $this->views('layouts/header');
        $this->views('konsultan/detail');
        $this->views('layouts/footer');
    }
}
