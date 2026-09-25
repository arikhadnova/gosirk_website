<?php
// app/core/Csrf.php
// Protects admin/auth actions from being triggered by another website (CSRF).
// - POST requests must carry the session token (hidden field "_token" or header X-CSRF-Token).
//   Admin pages add the field to every POST form automatically (see Csrf::injectIntoForms).
// - GET links that change data (delete, mark, ...) must come from this site itself.

class Csrf {
    const FIELD = '_token';

    public static function token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /** New token, e.g. after login. */
    public static function rotate() {
        unset($_SESSION['csrf_token']);
        return self::token();
    }

    public static function field() {
        return '<input type="hidden" name="' . self::FIELD . '" value="' . self::token() . '">';
    }

    /** True when the request carries the right token. */
    public static function validRequest() {
        $sent = $_POST[self::FIELD] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        return is_string($sent) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $sent);
    }

    /** True when the browser says the request was started from this site (or typed in the address bar). */
    public static function sameOrigin() {
        $site = $_SERVER['HTTP_SEC_FETCH_SITE'] ?? '';
        if ($site !== '') return in_array($site, ['same-origin', 'none'], true);

        // Older browsers / plain http: compare Origin or Referer with our host
        $from = $_SERVER['HTTP_ORIGIN'] ?? ($_SERVER['HTTP_REFERER'] ?? '');
        if ($from === '') return false;
        return strcasecmp((string) parse_url($from, PHP_URL_HOST), (string) parse_url('//' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST)) === 0;
    }

    /** Stop the request with a friendly message. */
    public static function reject($backTo) {
        http_response_code(419);
        $ajax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
            || strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false
            || !empty($_SERVER['HTTP_X_CSRF_TOKEN']);
        if ($ajax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Sesi halaman sudah kedaluwarsa. Muat ulang halaman lalu coba lagi.']);
            exit;
        }
        Flasher::setFlash('Permintaan ditolak.', 'Sesi halaman sudah kedaluwarsa atau permintaan tidak berasal dari panel admin. Silakan coba lagi.', 'danger');
        header('Location: ' . $backTo);
        exit;
    }

    /** Output-buffer callback: adds the token field right after every <form method="post">. */
    public static function injectIntoForms($html) {
        $field = self::field();
        return preg_replace('/<form\b(?=[^>]*\bmethod\s*=\s*["\']?post\b)[^>]*>/i', '$0' . $field, $html);
    }
}
