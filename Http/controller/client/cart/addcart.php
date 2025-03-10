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
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $quantity = isset($_GET['quantity']) ? max(1, intval($_GET['quantity'])) : 1;
    $product = $controller->showOneProductAddCart($id);
    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product['id']) {
            $item['quantity'] = min($item['quantity'] + $quantity, $product['stock']);
            $productExists = true;
            break;
        }
    }
    if (!$productExists) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image_url'],
            'quantity' => min($quantity, $product['stock']),
            'max_stock' => $product['stock']
        ];
    }
    // dd($_SESSION['cart']);
    // die();
    header("Location: /product/{$id}");
    exit();
} else {
    header("Location: /products");
    exit();
}
