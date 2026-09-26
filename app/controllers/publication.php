<?php

class Publication extends Controller {
    public function index() {
        $this->views('layouts/header');
        $this->views('publication/index');
        $this->views('layouts/footer');
    }

    public function gosirk() {
        $data = [
            'publications' => $this->model('Publication_model')->getByType('gosirk')
        ];
        $this->views('layouts/header');
        $this->views('publication/gosirk', $data);
        $this->views('layouts/footer');
    }

    // Visitor filled in the download form (or it was remembered in their browser): log it and allow the file
    public function request() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
            exit;
        }

        $errors = FormRules::validate('pub_request', $_POST);
        if ($errors) {
            echo json_encode(['status' => 'error', 'field_error' => true, 'message' => implode(' ', array_merge(...array_values($errors)))]);
            exit;
        }
        FormGuard::check('pubs', $_POST['email']);

        $pub = $this->model('Publication_model')->getById((int) $_POST['pub_id']);
        if (!$pub || $pub->is_paid || !$this->publicationFile($pub)) {
            echo json_encode(['status' => 'error', 'message' => 'Publikasi tidak ditemukan atau sedang tidak tersedia.']);
            exit;
        }

        $this->model('Collaboration_model')->logPublicationDownload((int) $pub->id, [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'organization' => trim($_POST['organization']),
            'jabatan' => trim($_POST['jabatan']),
        ]);

        $_SESSION['pub_access'][(int) $pub->id] = true;
        echo json_encode(['status' => 'success', 'url' => BASE_URL . 'publication/download/' . (int) $pub->id]);
        exit;
    }

    // Sends the PDF, only after the form above was submitted in this session. ?view=1 opens it in the browser.
    public function download($id = 0) {
        $pub = $this->model('Publication_model')->getById((int) $id);
        $file = $pub ? $this->publicationFile($pub) : false;
        if (!$file || $pub->is_paid || empty($_SESSION['pub_access'][(int) $id])) {
            header('Location: ' . BASE_URL . 'publication/' . ($pub->type ?? 'gosirk'));
            exit;
        }

        $name = trim(preg_replace('/[^A-Za-z0-9]+/', '-', $pub->title_id ?: 'publikasi-gosirk'), '-') . '.pdf';
        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: ' . (!empty($_GET['view']) ? 'inline' : 'attachment') . '; filename="' . $name . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($file);
        exit;
    }

    // Absolute path of a publication PDF inside assets/docs/, or false
    private function publicationFile($pub) {
        if (empty($pub->file_path)) return false;
        $dir = realpath(dirname(__DIR__, 2) . '/assets/docs');
        $file = realpath($dir . '/' . $pub->file_path);
        return $dir && $file && strpos($file, $dir . DIRECTORY_SEPARATOR) === 0 && is_file($file) ? $file : false;
    }

    public function reference() {
        $data = [
            'publications' => $this->model('Publication_model')->getByType('reference')
        ];
        $this->views('layouts/header');
        $this->views('publication/reference', $data);
        $this->views('layouts/footer');
    }
}
