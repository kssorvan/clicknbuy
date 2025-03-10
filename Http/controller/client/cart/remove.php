<?php


use Core\App;
use Core\Database;
use Core\Services\ImageUploadService;
use Http\controller\dashboard\products\ProductsController;

$db = App::resolve(Database::class);
$cloudinary = App::resolve(ImageUploadService::class);
$controller = new ProductsController($db, $cloudinary);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $productIdToRemove = $_POST['product_id'];

    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productIdToRemove) {
            return $item['id'] != $productIdToRemove;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
redirect('/cart');
exit();
