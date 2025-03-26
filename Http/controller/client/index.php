<?php

use Core\App;
use Core\Database;
use Core\Services\ImageUploadService;
use Http\controller\dashboard\products\ProductsController;

// Set up database and services first
$db = App::resolve(Database::class);
$cloudinary = App::resolve(ImageUploadService::class);
$controller = new ProductsController($db, $cloudinary);

// Fetch featured products from the database
$products = $db->query("
    SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    LIMIT 12
")->get();

// Now render the view with the products data
view("client/index.view.php", [
    'products' => $products
]);

// Handle other routes 
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/products' && $method === 'GET') {
    $controller->index('client');
}