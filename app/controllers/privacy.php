<?php

class privacy extends Controller {
    public function index() {
        $this->views('layouts/header', ['title' => 'Kebijakan Privasi']);
        $this->views('privacy/index', ['contact_email' => $this->model('Setting_model')->getByKey('contact_email')]);
        $this->views('layouts/footer');
    }
}
