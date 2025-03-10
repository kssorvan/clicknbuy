<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . "/../");
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../helpers.php';
// use Core\Session;
// use Core\ValidationException;
use Dotenv\Dotenv;
use Core\App;

session_start();
//require BASE_PATH . '/Core/function.php';
//require dirname(__DIR__) . '/Core/function.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$staticFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'css', 'js', 'svg', 'webp'];
$fileExtension = pathinfo($requestUri, PATHINFO_EXTENSION);

if (in_array($fileExtension, $staticFileExtensions)) {
    return false;
}

$router = new Core\Router();
$routes = require base_path('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);

$app = App::getContainer();
$db = $app->resolve('Core\Database');
echo "App running!";