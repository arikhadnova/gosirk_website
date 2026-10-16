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

    // Sends a free publication's PDF. ?view=1 opens it in the browser.
    public function download($id = 0) {
        $pub = $this->model('Publication_model')->getById((int) $id);
        $file = $pub ? $this->publicationFile($pub) : false;
        if (!$file || $pub->is_paid) {
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
