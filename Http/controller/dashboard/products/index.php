<?php

use Core\App;
use Core\Database;
use Core\Services\ImageUploadService;
use Http\controller\dashboard\products\ProductsController;

// Make sure these services are properly bound in your container
try {
    $db = App::resolve(Database::class);
    $cloudinary = App::resolve(ImageUploadService::class);
    
    // Instantiate controller
    $controller = new ProductsController($db, $cloudinary);
    
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Handle different routes
    if ($uri === '/products' && $method === 'GET') {
        $controller->index('client');
    } elseif ($uri === '/tbproducts' && $method === 'GET') {
        $controller->index('dashboard');
    } elseif ($uri === '/tbproducts' && $method === 'POST') {
        $controller->store($_POST);
    } elseif ($uri === '/tbproducts/update' && $method === 'POST') {
        $controller->update($_POST);
    } elseif ($uri === '/tbproducts/delete' && $method === 'POST') {
        $controller->destroy($_POST);
    }
} catch (\Exception $e) {
    // Log the error
    error_log($e->getMessage());
    // Display a user-friendly error message
    echo "An error occurred: " . $e->getMessage();
}