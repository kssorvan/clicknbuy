<?php
// Http/controller/client/index.php

use Core\App;
use Core\Database;

// Set up database
$db = App::resolve(Database::class);

// Get filter parameters (optional, for potential homepage filters)
$category = $_GET['category'] ?? null;
$params = [];

if ($category) {
    $whereClause = " AND p.category_id = ?";
    $params[] = $category;
} else {
    $whereClause = "";
}

// Base query for products
$baseQuery = "SELECT p.*, c.category_name 
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.category_id
              WHERE p.is_deleted = FALSE 
              AND (p.promotion_expiry IS NULL OR p.promotion_expiry > NOW())" . $whereClause;

// Fetch products for each section
$featuredProducts = $db->query(
    $baseQuery . " AND p.promotion_type = 'featured' ORDER BY p.created_at DESC LIMIT 8", 
    $params
)->get();

$bigSaleProducts = $db->query(
    $baseQuery . " AND p.promotion_type = 'big_sale' ORDER BY p.created_at DESC LIMIT 8", 
    $params
)->get();

$weeklyDealProducts = $db->query(
    $baseQuery . " AND p.promotion_type = 'weekly_deal' ORDER BY p.created_at DESC LIMIT 8", 
    $params
)->get();

$newArrivalProducts = $db->query(
    $baseQuery . " AND p.promotion_type = 'new_arrival' ORDER BY p.created_at DESC LIMIT 8", 
    $params
)->get();

// Get categories for possible filtering
$categories = $db->query("SELECT * FROM categories WHERE is_deleted = FALSE ORDER BY category_name")->get();

// Now render the view with the products data
view("client/index.view.php", [
    'featuredProducts' => $featuredProducts,
    'bigSaleProducts' => $bigSaleProducts,
    'weeklyDealProducts' => $weeklyDealProducts,
    'newArrivalProducts' => $newArrivalProducts,
    'categories' => $categories
]);