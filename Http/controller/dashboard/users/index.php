<?php

namespace Http\controller\dashboard\users;

use Core\App;
use Core\Database;
use Core\Services\ImageUploadService;

$db = App::resolve(Database::class);
$cloudinary = App::resolve(ImageUploadService::class);
$controller = new UserController($db, $cloudinary);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/tbusers' && $method === 'GET') {
    $controller->index();
} elseif ($uri === '/tbusers/delete' && $method === 'POST') {
    $controller->destroy($_POST);
}
// } elseif ($uri === '/tbproducts' && $method === 'POST') {
//     if (!isset($_POST['product_id'])) {
//         $controller->store($_POST);
//     }
// } elseif ($uri === '/tbproducts/update' && $method === 'POST') {
//     $controller->update($_POST);
