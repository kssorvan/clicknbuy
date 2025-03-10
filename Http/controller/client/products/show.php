<?php

use Core\App;
use Core\Database;
use Core\Services\ImageUploadService;
use Http\controller\dashboard\products\ProductsController;

$db = App::resolve(Database::class);
$cloudinary = App::resolve(ImageUploadService::class);
$controller = new ProductsController($db, $cloudinary);

$uri = $_SERVER['REQUEST_URI'];
$id = basename($uri);

if (is_numeric($id)) {
    $controller->showOneProduct($id);
} else {
    header('Location: /products');
    exit();
}
