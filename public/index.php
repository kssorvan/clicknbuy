<?php
use Core\App;
use Core\Router;

// Define the base path
define('BASE_PATH', dirname(__DIR__));

// Load environment variables (if using Dotenv)
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}

// Enable error reporting for development
if (getenv('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // In production, disable display_errors and log errors
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_PATH . '/logs/error.log');
}

// Include helper functions (once)
require_once BASE_PATH . '/helpers.php';

// Load Composer autoloader and bootstrap
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/bootstrap.php';

// Configure session settings
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session cookies
ini_set('session.use_strict_mode', 1); // Prevent session fixation
ini_set('session.cookie_secure', 0);   // Set to 1 if using HTTPS in production

// Start the session
session_start();

// Handle static file requests
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$staticFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'css', 'js', 'svg', 'webp', 'ico', 'woff', 'woff2'];
$fileExtension = pathinfo($uri, PATHINFO_EXTENSION);

if (in_array($fileExtension, $staticFileExtensions)) {
    return false; // Let the web server handle static files
}

// Set up routing
$router = new Router();
$routes = require base_path('routes.php');
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);