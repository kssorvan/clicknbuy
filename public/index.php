<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use Core\App;
use Core\Router;

// Define the base path
define('BASE_PATH', dirname(__DIR__));

// Load Composer autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Load environment variables
try {
    if (file_exists(BASE_PATH . '/.env')) {
        $dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();
    } else {
        error_log('Warning: .env file not found in ' . BASE_PATH);
    }
} catch (\Exception $e) {
    error_log('Dotenv error: ' . $e->getMessage());
    die('Failed to load environment variables. Check logs for details.');
}

// Enable error reporting based on environment
if (getenv('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    $logDir = BASE_PATH . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    ini_set('error_log', $logDir . '/error.log');
}

// Include helper functions
require_once BASE_PATH . '/helpers.php';

// Load bootstrap
require_once BASE_PATH . '/bootstrap.php';

// Configure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', getenv('APP_ENV') === 'production' ? 1 : 0);

// Start the session
session_start();

// Handle static file requests
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$staticFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'css', 'js', 'svg', 'webp', 'ico', 'woff', 'woff2'];
$fileExtension = pathinfo($uri, PATHINFO_EXTENSION);

if (in_array($fileExtension, $staticFileExtensions)) {
    $filePath = BASE_PATH . '/public' . $uri;
    if (file_exists($filePath)) {
        return false; // Let the web server handle static files
    }
    http_response_code(404);
    exit;
}

// Set up routing
$router = new Router();
$routes = require base_path('routes.php');

// Register all the routes
foreach ($routes as $route) {
    $router->add($route['method'], $route['uri'], $route['controller']);
    if (!empty($route['middleware'])) {
        $router->only($route['middleware']);
    }
}

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$router->route($uri, $method);