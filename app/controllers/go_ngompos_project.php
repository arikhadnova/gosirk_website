<?php

class go_ngompos_project extends Controller {
    public function index() {
        $data = [
            'page' => 'go_ngompos_project',
            'hero' => $this->model('Hero_model')->getByPage('go_ngompos_project'),
            'impacts' => $this->model('Impact_model')->getAllByPage('go_ngompos_project'),
            'hero_transition' => $this->model('Setting_model')->getByKey('go_ngompos_project_hero_transition') ?: 'slide',
            'hero_logo' => $this->model('Setting_model')->getByKey('go_ngompos_project_hero_logo') ?: 'logo-go-ngompos.svg',
            'about_section' => $this->model('PageSection_model')->getByPageAndSection('go_ngompos_project', 'about'),
            'concept_notes' => $this->model('Collaboration_model')->getActiveDocumentsByType('concept_note'),
            'programs' => $this->model('GnpProgram_model')->getAll(),
            'programs_section' => $this->model('PageSection_model')->getByPageAndSection('go_ngompos_project', 'programs')
        ];

        $this->views('layouts/header', $data);
        $this->views('go_ngompos_project/index', $data);
        $this->views('layouts/footer');
    }
}
