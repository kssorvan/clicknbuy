<?php
// Http/controller/client/financing/calculator.php


use Core\App;
use Core\Database;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/motorcycles');
    exit();
}

$db = App::resolve(Database::class);

// Get motorcycle details
$motorcycle = $db->query("
    SELECT p.*, mb.brand_name, ms.model_year
    FROM products p
    LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
    LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
    WHERE p.product_id = ?
", [$id])->find();

if (!$motorcycle) {
    abort(404);
}

// Get financing options
$financingOptions = $db->query("
    SELECT fo.*
    FROM financing_options fo
    JOIN product_financing pf ON fo.option_id = pf.option_id
    WHERE pf.product_id = ?
    ORDER BY fo.interest_rate ASC
", [$id])->get();

// If no specific options, get default options
if (empty($financingOptions)) {
    $financingOptions = $db->query("
        SELECT * FROM financing_options
        ORDER BY interest_rate ASC
    ")->get();
}

view("client/financing/calculator.view.php", [
    'motorcycle' => $motorcycle,
    'financingOptions' => $financingOptions
]);