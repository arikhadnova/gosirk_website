<?php
// app/core/Controller.php

class Controller {
    // Model loader
    public function model($model) {
        require_once dirname(__DIR__) . '/models/' . $model . '.php';
        return new $model();
    }

    // Primary view loader used across controllers (keeps compatibility)

    public function views($view, $data = []) {
        if (!empty($data) && is_array($data)) {
            extract($data);
        }

        // Normalize common folder name typos (eg. 'layout' -> 'layouts')
        if (strpos($view, 'layout/') === 0) {
            $view = 'layouts/' . substr($view, strlen('layout/'));
        }

        $base = dirname(__DIR__); // app/
        $path = $base . '/views/' . $view . '.php';
        if (file_exists($path)) {
            require_once $path;
            return;
        }

        // Fallback: try the view path as given (keeps previous behavior)
        require_once $base . '/views/' . $view . '.php';
    }

    // WhatsApp number from admin setting `contact_whatsapp`, normalized to 62xxxxxxxxxx
    public function waNumber() {
        static $number = null;
        if ($number === null) {
            $raw = $this->model('Setting_model')->getByKey('contact_whatsapp') ?: '';
            $number = preg_replace('/[^0-9]/', '', $raw);
            if (strpos($number, '0') === 0) {
                $number = '62' . substr($number, 1);
            }
        }
        return $number;
    }

    // wa.me link with optional pre-filled message
    public function waLink($message = '') {
        $link = 'https://wa.me/' . $this->waNumber();
        return $message !== '' ? $link . '?text=' . urlencode($message) : $link;
    }

    // Extract the 11-char YouTube video ID from any common YouTube URL format
    public function youtubeId($url) {
        return preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', (string) $url, $m) ? $m[1] : null;
    }

    // Backwards-compatible alias
    public function view($view, $data = []) {
        return $this->views($view, $data);
    }
}
