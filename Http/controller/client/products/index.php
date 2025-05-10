<?php
// Http/controller/client/products/index.php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

// Get filter parameters
$category = $_GET['category'] ?? null;
$minPrice = $_GET['price_min'] ?? null;
$maxPrice = $_GET['price_max'] ?? null;
$sort = $_GET['sort'] ?? 'default';

// Base query for products
$baseQuery = "SELECT p.*, c.category_name 
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.category_id
              WHERE p.is_deleted = FALSE 
              AND (p.promotion_expiry IS NULL OR p.promotion_expiry > NOW())";
$params = [];

if ($category) {
    $baseQuery .= " AND p.category_id = ?";
    $params[] = $category;
}

if ($minPrice) {
    $baseQuery .= " AND p.price >= ?";
    $params[] = $minPrice;
}

if ($maxPrice) {
    $baseQuery .= " AND p.price <= ?";
    $params[] = $maxPrice;
}

// Apply sorting
$sortClause = "";
switch ($sort) {
    case 'price_asc':
        $sortClause = " ORDER BY p.price ASC";
        break;
    case 'price_desc':
        $sortClause = " ORDER BY p.price DESC";
        break;
    case 'newest':
        $sortClause = " ORDER BY p.created_at DESC";
        break;
    default:
        $sortClause = " ORDER BY p.name ASC";
}

// Fetch all products based on filters
$products = $db->query($baseQuery . $sortClause, $params)->get();

// Get all categories for navigation and filtering
$categories = $db->query("SELECT c.*, COUNT(p.product_id) as product_count 
                        FROM categories c
                        LEFT JOIN products p ON c.category_id = p.category_id AND p.is_deleted = FALSE
                        WHERE c.is_deleted = FALSE 
                        GROUP BY c.category_id
                        ORDER BY c.category_name")->get();

view("client/products/index.view.php", [
    'products' => $products,
    'categories' => $categories,
    'currentCategory' => $category,
    'minPrice' => $minPrice,
    'maxPrice' => $maxPrice,
    'currentSort' => $sort
]);