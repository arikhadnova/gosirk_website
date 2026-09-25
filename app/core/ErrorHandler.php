<?php
// app/core/ErrorHandler.php
// Uncaught errors: always logged; technical details are shown only when APP_ENV=local.

class ErrorHandler {
    private static $debug = false;

    public static function register($debug) {
        self::$debug = (bool) $debug;
        error_reporting(E_ALL);
        ini_set('display_errors', self::$debug ? '1' : '0');
        ini_set('log_errors', '1');
        set_exception_handler([self::class, 'handle']);
    }

    public static function handle($e) {
        error_log('[GoSirk] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

        while (ob_get_level() > 0) ob_end_clean();

        $path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
        $isPost = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
        $detail = self::$debug ? ' (' . $e->getMessage() . ')' : '';

        // Admin save: go back to the form with a message and the typed input kept
        if ($isPost && preg_match('#(^|/)admin(/|$)#', $path) && !headers_sent() && class_exists('Flasher')) {
            Flasher::keepOldInput($_POST);
            Flasher::setFlash('Terjadi kesalahan saat menyimpan.', 'Silakan coba lagi. Jika masih gagal, hubungi developer.' . htmlspecialchars($detail), 'danger');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (defined('BASE_URL') ? BASE_URL . 'admin' : '/')));
            exit;
        }

        http_response_code(500);

        // Public AJAX forms expect JSON
        if ($isPost && preg_match('#(contact/store|collaboration/request)$#', $path)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada sistem. Silakan coba lagi nanti.' . $detail]);
            exit;
        }

        $home = defined('BASE_URL') ? BASE_URL : '/';
        $debugBlock = self::$debug
            ? '<pre style="text-align:left;white-space:pre-wrap;background:#f8f9fa;padding:1rem;border-radius:8px;font-size:12px;margin-top:1.5rem;">'
              . htmlspecialchars(get_class($e) . ': ' . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine() . "\n\n" . $e->getTraceAsString())
              . '</pre>'
            : '';
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<title>Terjadi Kesalahan</title></head>'
            . '<body style="font-family:system-ui,sans-serif;background:#f5f7fb;color:#212529;margin:0;padding:24px;">'
            . '<div style="max-width:640px;margin:10vh auto;background:#fff;border-radius:16px;padding:40px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.06);">'
            . '<h1 style="margin:0 0 12px;font-size:24px;">Maaf, terjadi kesalahan</h1>'
            . '<p style="color:#6c757d;margin:0 0 24px;">Halaman ini tidak dapat ditampilkan saat ini. Silakan coba lagi beberapa saat lagi.</p>'
            . '<a href="' . htmlspecialchars($home) . '" style="display:inline-block;background:#0D4A7C;color:#fff;text-decoration:none;padding:10px 24px;border-radius:999px;">Kembali ke Beranda</a>'
            . $debugBlock
            . '</div></body></html>';
        exit;
    }
}
