<?php
// app/core/FormGuard.php
// Spam protection for the public forms (contact, document requests, publication downloads).
// Invisible to real visitors - no captcha:
//  1. Honeypot: a hidden "website" field that only bots fill in. Bots get a fake "success".
//  2. Form token: signed page-load time, added to every form post by layouts/footer.php.
//     Posts without it (scripts calling the endpoint directly) or sent within MIN_SECONDS are refused.
//  3. Rate limit per email and per IP (LoginThrottle scopes 'contact', 'docs', 'pubs').

class FormGuard {
    const MIN_SECONDS = 3;
    const MAX_AGE = 86400;   // a page left open for a day must be reloaded
    const HONEYPOT = 'website';

    private static function secret() {
        return hash('sha256', (defined('DB_PASS') ? DB_PASS : '') . '|' . (defined('DB_NAME') ? DB_NAME : '') . '|gosirk-forms');
    }

    private static function sign($time) {
        return substr(hash_hmac('sha256', (string) $time, self::secret()), 0, 32);
    }

    /** Token for <meta name="form-token">: page-load time + signature */
    public static function token() {
        $t = time();
        return $t . '.' . self::sign($t);
    }

    /** Hidden field that real visitors never see or fill in */
    public static function honeypot() {
        return '<div class="hp-field" aria-hidden="true"><label>Website</label><input type="text" name="' . self::HONEYPOT . '" tabindex="-1" autocomplete="off"></div>';
    }

    /**
     * Stops the request (JSON answer) when it looks like spam or the limit is reached.
     * Call after the normal field validation, before anything is saved or emailed.
     */
    public static function check($scope, $ident) {
        if (trim((string) ($_POST[self::HONEYPOT] ?? '')) !== '') {
            self::stop(true, ''); // bot: pretend it worked
        }

        [$t, $sig] = array_pad(explode('.', (string) ($_POST['_ft'] ?? ''), 2), 2, '');
        if (!ctype_digit($t) || !hash_equals(self::sign((int) $t), $sig) || time() - (int) $t > self::MAX_AGE) {
            self::stop(false, 'Sesi halaman sudah kedaluwarsa. Muat ulang halaman lalu kirim lagi.');
        }
        if (time() - (int) $t < self::MIN_SECONDS) {
            self::stop(false, 'Formulir terkirim terlalu cepat. Tunggu sebentar lalu kirim lagi.');
        }

        $wait = LoginThrottle::lockedFor($scope, $ident);
        if ($wait > 0) {
            self::stop(false, 'Terlalu banyak permintaan dari Anda. Silakan coba lagi dalam ' . LoginThrottle::waitLabel($wait) . '.');
        }
        LoginThrottle::hit($scope, $ident);
    }

    private static function stop($fakeSuccess, $message) {
        if (!headers_sent()) header('Content-Type: application/json');
        echo json_encode($fakeSuccess
            ? ['status' => 'success', 'message' => 'Terima kasih, permintaan Anda sudah kami terima.']
            : ['status' => 'error', 'message' => $message]);
        exit;
    }
}
