<?php

class collaboration extends Controller {
    public function index() 
    {
        $collaborationModel = $this->model('Collaboration_model');
        $data = [
            'docs' => $collaborationModel->getActiveDocumentsByType('executive_summary'),
            'compro' => $collaborationModel->getActiveDocumentsByType('company_profile')[0] ?? null
        ];

        $this->views('layouts/header', $data);
        $this->views('collaboration/index', $data);
        $this->views('layouts/footer');
    }

    public function request() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
            exit;
        }

        $errors = FormRules::validate('doc_request', $_POST);
        if ($errors) {
            echo json_encode(['status' => 'error', 'message' => implode(' ', array_merge(...array_values($errors)))]);
            exit;
        }

        $collaborationModel = $this->model('Collaboration_model');
        $doc = $collaborationModel->getDocumentById((int) $_POST['doc_id']);
        $attachmentPath = $doc ? realpath(__DIR__ . '/../storage/documents/' . $doc->file_path) : false;
        if (!$doc || ($doc->status ?? 'active') !== 'active' || !$attachmentPath) {
            echo json_encode(['status' => 'error', 'message' => 'Dokumen tidak ditemukan atau sedang tidak tersedia.']);
            exit;
        }

        $data = [
            'doc_id' => (int) $doc->id,
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'organization' => trim($_POST['organization']),
            'jabatan' => trim($_POST['jabatan'])
        ];

        $requestId = $collaborationModel->logRequest($data);
        if (!$requestId) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memproses permintaan. Silakan coba lagi nanti.']);
            exit;
        }

        // Email content comes from the templates in Admin > Email Settings (values escaped by Mail::render)
        $vars = [
            'nama' => $data['name'],
            'email' => $data['email'],
            'instansi' => $data['organization'],
            'jabatan' => $data['jabatan'],
            'dokumen' => $doc->title_id,
        ];

        $autoSend = ((int) ($doc->auto_send ?? 1)) === 1;
        $sentToUser = false;
        if ($autoSend) {
            $userMail = Mail::render('doc_user', $vars);
            $sentToUser = Mail::send($data['email'], $userMail['subject'], $userMail['html'], $attachmentPath);
        }

        $collaborationModel->setDelivery($requestId, !$autoSend ? 'pending' : ($sentToUser ? 'sent' : 'failed'));

        $vars['status_pengiriman'] = !$autoSend
            ? 'PERLU DIKIRIM MANUAL (pengiriman otomatis dinonaktifkan untuk dokumen ini)'
            : ($sentToUser ? 'terkirim otomatis' : 'GAGAL, mohon kirim manual');
        $adminMail = Mail::render('doc_admin', $vars);
        Mail::sendToAdmin(($autoSend && $sentToUser ? '' : '[Perlu Tindak Lanjut] ') . $adminMail['subject'], $adminMail['html']);

        if (!$autoSend) {
            echo json_encode(['status' => 'success', 'message' => 'Permintaan Anda sudah kami terima. Tim kami akan meninjau dan mengirimkan dokumen ke email Anda.']);
            exit;
        }

        if (!$sentToUser) {
            error_log('[GoSirk] Email dokumen gagal dikirim ke ' . $data['email']);
            echo json_encode(['status' => 'error', 'message' => 'Permintaan Anda sudah kami terima, tetapi email dokumen gagal dikirim. Tim kami akan mengirimkannya secara manual, atau silakan coba lagi beberapa saat lagi.']);
            exit;
        }

        echo json_encode(['status' => 'success', 'message' => 'Permintaan berhasil dikirim! Silakan cek email Anda (pastikan cek folder spam jika tidak ditemukan).']);
        exit;
    }
}
