<?php
// Load config constants (also reads .env)
require_once __DIR__ . '/config.php';

// Admin login lifetime in hours (.env SESSION_LIFETIME_HOURS, default 12)
define('SESSION_LIFETIME', (int) round(((float) (getenv('SESSION_LIFETIME_HOURS') ?: 12)) * 3600));

if (!session_id()) {
    // Session cookie: not readable by JS, not sent on cross-site form posts, HTTPS-only when on HTTPS
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Lax']);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.gc_maxlifetime', (string) SESSION_LIFETIME); // keep session files at least as long as a login lasts
    session_start();
}

// Admin login ends after SESSION_LIFETIME seconds without activity (SESSION_LIFETIME_HOURS in .env)
if (isset($_SESSION['admin_logged_in'])) {
    if (time() - ($_SESSION['last_activity'] ?? time()) > SESSION_LIFETIME) {
        $_SESSION = [];
        session_regenerate_id(true);
    } else {
        $_SESSION['last_activity'] = time();
    }
}

// Set Timezone
date_default_timezone_set('Asia/Jakarta');


// Errors: details on screen only for APP_ENV=local; production shows a friendly page and logs
require_once __DIR__ . '/core/ErrorHandler.php';
ErrorHandler::register((getenv('APP_ENV') ?: 'production') === 'local');
require_once __DIR__ . '/core/Database.php';


// Load core framework
require_once __DIR__ . '/core/app.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Flasher.php';
require_once __DIR__ . '/core/Csrf.php';
require_once __DIR__ . '/core/LoginThrottle.php';
require_once __DIR__ . '/core/Validator.php';
require_once __DIR__ . '/core/FormRules.php';
require_once __DIR__ . '/core/PageImages.php';
require_once __DIR__ . '/core/AdminNav.php';
require_once __DIR__ . '/core/PrivacyPolicy.php';
require_once __DIR__ . '/core/Upload.php';
require_once __DIR__ . '/core/Mail.php';