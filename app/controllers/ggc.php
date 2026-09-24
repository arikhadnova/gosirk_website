<?php

class ggc extends Controller {
    public function index() {
        $data = [
            'hero' => $this->model('Hero_model')->getByPage('ggc'),
            'impacts' => $this->model('Impact_model')->getByPage('ggc'),
            'actions' => $this->model('GgcAction_model')->getAll(),
            'hero_transition' => $this->model('Setting_model')->getByKey('ggc_hero_transition') ?: 'slide',
            'hero_logo' => $this->model('Setting_model')->getByKey('ggc_hero_logo') ?: 'logo-ggc.png',
            'about_section' => $this->model('PageSection_model')->getByPageAndSection('ggc', 'about')
        ];
        $this->views('layouts/header', $data);
        $this->views('ggc/index', $data);
        $this->views('layouts/footer');
    }
}

