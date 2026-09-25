<?php

class implementasi_partner extends Controller {
    public function index() {
        $data = [
            'hero' => $this->model('Hero_model')->getByPage('partner'),
            'impacts' => $this->model('Impact_model')->getByPage('clocc'),
            'home_impacts' => $this->model('Impact_model')->getByPage('home'),
            'testimonials' => $this->model('Testimonial_model')->getByPage('implentasi_partner'),
            'faqs' => $this->model('Faq_model')->getByPage('implentasi_partner'),
            'partners' => $this->model('Partner_model')->getAll(),
            'portfolios' => $this->model('Portfolio_model')->getByShowPartner(),
            'villages' => $this->model('PilotVillage_model')->getAll(),
            'hero_transition' => $this->model('Setting_model')->getByKey('partner_hero_transition') ?: 'slide',
            'about_section' => $this->model('PageSection_model')->getByPageAndSection('partner', 'about'),
            'highlights_section' => $this->model('PageSection_model')->getByPageAndSection('partner', 'highlights')
        ];
        $this->views('layouts/header', $data);
        $this->views('implentasi_partner/index', $data);
        $this->views('layouts/footer');
    }
}
