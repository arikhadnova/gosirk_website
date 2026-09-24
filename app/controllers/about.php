<?php
// app/controllers/about.php

class about extends Controller {
    public function index() 
    {
        $data['founders'] = $this->model('Founder_model')->getAll();
        $data['about_section'] = $this->model('PageSection_model')->getByPageAndSection('about', 'about');
        $this->views('layouts/header', $data);
        $this->views('about/index', $data);
        $this->views('layouts/footer');
    }
}
