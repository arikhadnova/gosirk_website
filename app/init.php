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
require_once __DIR__ . '/core/FormGuard.php';
require_once __DIR__ . '/core/EnField.php';
require_once __DIR__ . '/core/Validator.php';
require_once __DIR__ . '/core/FormRules.php';
require_once __DIR__ . '/core/PageImages.php';
require_once __DIR__ . '/core/AdminNav.php';
require_once __DIR__ . '/core/PrivacyPolicy.php';
require_once __DIR__ . '/core/ImageOptimizer.php';
require_once __DIR__ . '/core/Upload.php';
require_once __DIR__ . '/core/Mail.php';

/**
 * URL of a file in assets/ with a version that only changes when the file changes,
 * so browsers can cache CSS/JS/images between pages and still get updates right after a deploy.
 */
function asset_v($relative) {
    $file = dirname(__DIR__) . '/assets/' . ltrim($relative, '/');
    return ASSETS_URL . ltrim($relative, '/') . '?v=' . (is_file($file) ? filemtime($file) : '1');
}

/**
 * Google "Material Symbols" icon font, limited to the icons the site uses and the one style it
 * uses (FILL 0, weight 400, grade 0, size 48). The full font is ~4 MB; this subset is a few KB.
 * Using a new icon? Add its name here (alphabetical order is required by Google Fonts).
 */
const MATERIAL_ICONS = ['analytics', 'arrow_forward', 'calendar_today', 'check_circle', 'chevron_left', 'chevron_right', 'description', 'docs', 'download', 'eco', 'event_available', 'fact_check', 'folder_off', 'folder_open', 'format_quote', 'group', 'groups', 'handshake', 'hub', 'image', 'insights', 'library_books', 'lock', 'lock_reset', 'mail', 'manage_search', 'map', 'menu_book', 'person', 'query_stats', 'search', 'target', 'track_changes', 'verified'];

function material_symbols_url() {
    $icons = MATERIAL_ICONS;
    sort($icons);
    return 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&icon_names=' . implode(',', $icons) . '&display=block';
}
