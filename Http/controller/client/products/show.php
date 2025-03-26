// Http/controller/client/products/show.php
<?php

use Core\App;
use Core\Database;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/products');
    exit();
}

$db = App::resolve(Database::class);

// Get product details
$product = $db->query("
    SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.product_id = ?
", [$id])->find();

if (!$product) {
    abort(404);
}

// Get motorcycle specs if available
$specs = $db->query("
    SELECT ms.*, mb.brand_name
    FROM motorcycle_specs ms
    LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
    WHERE ms.product_id = ?
", [$id])->find();

// Get motorcycle features
$features = $db->query("
    SELECT * FROM motorcycle_features
    WHERE product_id = ?
", [$id])->get();

// Get related products
$relatedProducts = $db->query("
    SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.category_id = ? AND p.product_id != ?
    LIMIT 4
", [$product['category_id'], $id])->get();

view("client/products/show.view.php", [
    'product' => $product,
    'specs' => $specs,
    'features' => $features,
    'relatedProducts' => $relatedProducts
]);