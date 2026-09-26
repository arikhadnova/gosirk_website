<?php

class Contact extends Controller {
    public function index() {
        $data = ['settings' => $this->model('Setting_model')->getAll()];
        $this->views('layouts/header');
        $this->views('contact/index', $data);
        $this->views('layouts/footer');
    }

    public function store() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
            exit;
        }

        $errors = FormRules::validate('contact', $_POST);
        if ($errors) {
            echo json_encode(['status' => 'error', 'message' => implode(' ', array_merge(...array_values($errors)))]);
            exit;
        }
        FormGuard::check('contact', $_POST['email']);

        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'message' => trim($_POST['message'])
        ];

        if (!$this->model('Contact_model')->add($data)) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan. Silakan coba lagi nanti.']);
            exit;
        }

        // Template from Admin > Email Settings; visitor input is escaped by Mail::render()
        $mail = Mail::render('contact_admin', ['nama' => $data['name'], 'email' => $data['email'], 'pesan' => $data['message']]);
        if (!Mail::sendToAdmin($mail['subject'], $mail['html'])) {
            // The message is stored and visible in the dashboard, so the visitor still succeeded
            error_log('[GoSirk] Notifikasi email pesan kontak gagal dikirim ke admin.');
        }

        echo json_encode(['status' => 'success', 'message' => 'Pesan Anda telah terkirim!']);
        exit;
    }
}

