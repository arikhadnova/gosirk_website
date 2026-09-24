<?php
// config/config.php

// Load .env file manually if it exists
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Base URL Configuration (production: https://gosirk.co.id/)
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/gosirk_website/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Site Configuration
define('SITE_NAME', 'Go Circular Indonesia');
define('SITE_DESCRIPTION', 'Solusi pengelolaan sampah berkelanjutan');

// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', getenv('DB_NAME') ?: 'gosirk_db');

// Main Email Configuration (Brevo API)
define('BREVO_API_KEY', getenv('BREVO_API_KEY') ?: 'YOUR_BREVO_API_KEY_HERE'); // Loaded from .env file
define('MAIL_FROM', 'triuliamrikhadnova2000@gmail.com'); // Must be an authorized sender in Brevo
define('MAIL_FROM_NAME', 'Go Circular Indonesia');

// Admin Notification Email
define('MAIL_ADMIN_ADDRESS', 'triuliamrikhadnova2000@gmail.com'); // email to receive notifications
