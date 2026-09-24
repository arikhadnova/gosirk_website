<?php

class partnership extends Controller {
    public function index() {
        $portfolioModel = $this->model('Portfolio_model');
        $data = [
            'hero' => $this->model('Hero_model')->getByPage('partner'),
            'portfolios' => $portfolioModel->getAll(),
            'partners' => $this->model('Partner_model')->getAll(),
            'impacts' => $this->model('Impact_model')->getByPage('partnership'),
            'testimonials' => $this->model('Testimonial_model')->getByPage('partnership'),
            'faqs' => $this->model('Faq_model')->getByPage('partnership'),
            'settings' => $this->model('Setting_model')->getAll(),
            'hero_transition' => $this->model('Setting_model')->getByKey('partner_hero_transition') ?: 'slide'
        ];
        $this->views('layouts/header', $data);
        $this->views('partnership/index', $data);
        $this->views('layouts/footer');
    }
}

