<?php
// app/core/App.php

class App {
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        // Search engine files, generated from the database (not counted as visits)
        $path = $this->parseUrl() ?: [];
        if (count($path) === 1 && in_array($path[0], ['sitemap.xml', 'robots.txt'], true)) {
            require_once dirname(__DIR__) . '/core/Sitemap.php';
            Sitemap::serve($path[0]);
        }

        // Log Visitor Hit
        require_once dirname(__DIR__) . '/models/Visitor_model.php';
        $visitorModel = new Visitor_model();
        $visitorModel->logHit();

        $url = $this->parseUrl() ?: [];


        // Maintenance Mode Check
        require_once dirname(__DIR__) . '/models/Setting_model.php';
        $settingModel = new Setting_model();
        $isMaintenance = $settingModel->getByKey('is_maintenance');

        if ($isMaintenance == '1') {
            // Allow if admin is logged in OR accessing auth controller
            $isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
            $isAuthPage = isset($url[0]) && $url[0] === 'auth';
            $isAdminPage = isset($url[0]) && $url[0] === 'admin';

            if (!$isAdmin && !$isAuthPage && !$isAdminPage) {
                require_once dirname(dirname(__DIR__)) . '/maintenance.php';
                exit;
            }
        }

        $base = dirname(__DIR__); // app/

        // Check for controller (ensure index exists before accessing)
        $isPanel = isset($url[0]) && in_array($url[0], ['admin', 'auth'], true);
        if (isset($url[0]) && preg_match('/^[a-z_]+$/', $url[0]) && file_exists($base . '/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        } elseif (isset($url[0])) {
            (new Controller)->notFound(); // unknown address: real 404 instead of silently showing Home
        }

        require_once $base . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check for method
        if (isset($url[1]) && method_exists($this->controller, $url[1]) && is_callable([$this->controller, $url[1]])) {
            $this->method = $url[1];
            unset($url[1]);
        } elseif (isset($url[1]) && !$isPanel) {
            $this->controller->notFound(); // e.g. /about/xyz
        }

        // Get params
        if( !empty($url) ){
            $this->params = array_values($url);
        }

        // Call controller method with params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() 
    {
        $url = $_GET['url'] ?? null;

        // Fallback for servers without .htaccess rewrites (eg. nginx / Laravel Herd)
        if ($url === null && isset($_SERVER['REQUEST_URI'])) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '';
            $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?: '/';
            if (strpos($path, $basePath) === 0) {
                $path = substr($path, strlen($basePath));
            }
            $path = trim(preg_replace('#^index\.php#', '', ltrim($path, '/')), '/');
            $url = $path !== '' ? $path : null;
            if ($url !== null) {
                $_GET['url'] = $url; // keep views that read $_GET['url'] working
            }
        }

        if($url !== null) {
            $url = rtrim($url, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}