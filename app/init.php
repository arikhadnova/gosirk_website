<?php
if (!session_id()) session_start();

// Set Timezone
date_default_timezone_set('Asia/Jakarta');

// Load config constants (also reads .env)
require_once __DIR__ . '/config.php';

// Errors: details on screen only for APP_ENV=local; production shows a friendly page and logs
require_once __DIR__ . '/core/ErrorHandler.php';
ErrorHandler::register((getenv('APP_ENV') ?: 'production') === 'local');
require_once __DIR__ . '/core/Database.php';


// Load core framework
require_once __DIR__ . '/core/app.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Flasher.php';
require_once __DIR__ . '/core/Validator.php';
require_once __DIR__ . '/core/FormRules.php';
require_once __DIR__ . '/core/Upload.php';
require_once __DIR__ . '/core/Mail.php';