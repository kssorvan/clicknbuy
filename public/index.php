
<?php


use Dotenv\Dotenv;
use Core\App;




// if (!defined('BASE_PATH')) {
//     define('BASE_PATH', __DIR__ . "/../");
// }

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the base path
define('BASE_PATH', dirname(__DIR__));

// Include helper functions
require BASE_PATH . '/helpers.php';


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../helpers.php';



session_start();

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

$app = App::container(); 
$db = $app->resolve('Core\Database');
echo "App running!";