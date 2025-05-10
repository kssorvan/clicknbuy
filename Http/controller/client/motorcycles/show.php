<?php
// Http/controller/client/motorcycles/show.php


use Core\App;
use Core\Database;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/motorcycles');
    exit();
}

$db = App::resolve(Database::class);

// Get motorcycle with specs
$motorcycle = $db->query("
    SELECT p.*, c.category_name, ms.*, mb.brand_name, mb.logo_url as brand_logo
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
    LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
    WHERE p.product_id = ?
", [$id])->find();

if (!$motorcycle) {
    abort(404);
}

// Get additional images
$additionalImages = $db->query("
    SELECT * FROM product_images
    WHERE product_id = ?
    ORDER BY image_order ASC
", [$id])->get();

// Get features
$features = $db->query("
    SELECT * FROM motorcycle_features
    WHERE product_id = ?
", [$id])->get();

// Get reviews
$reviews = $db->query("
    SELECT r.*, u.name as user_name
    FROM product_reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
", [$id])->get();

// Get financing options
$financingOptions = $db->query("
    SELECT fo.*
    FROM financing_options fo
    JOIN product_financing pf ON fo.option_id = pf.option_id
    WHERE pf.product_id = ?
", [$id])->get();

// If no specific financing options, get default options
if (empty($financingOptions)) {
    $financingOptions = $db->query("
        SELECT * FROM financing_options
        ORDER BY interest_rate ASC
    ")->get();
}

// Get related motorcycles
$relatedMotorcycles = $db->query("
    SELECT p.*, c.category_name, ms.model_year, ms.condition, mb.brand_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
    LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
    WHERE p.category_id = ? AND p.product_id != ?
    ORDER BY p.created_at DESC
    LIMIT 4
", [$motorcycle['category_id'], $id])->get();

view("client/motorcycles/show.view.php", [
    'motorcycle' => $motorcycle,
    'additionalImages' => $additionalImages,
    'features' => $features,
    'reviews' => $reviews,
    'financingOptions' => $financingOptions,
    'relatedMotorcycles' => $relatedMotorcycles
]);