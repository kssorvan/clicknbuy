<?php
// Http/controller/client/index.php

use Core\App;
use Core\Database;

// Set up database
$db = App::resolve(Database::class);

// Fetch featured products for the homepage
$products = $db->query("
    SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    LIMIT 12
")->get();

// Render the homepage
view("client/index.view.php", [
    'products' => $products
]);