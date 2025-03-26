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

// Build the query
$query = "SELECT p.*, c.category_name 
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.category_id
          WHERE 1=1";
$params = [];

// Apply filters
if ($category) {
    $query .= " AND p.category_id = ?";
    $params[] = $category;
}

if ($minPrice) {
    $query .= " AND p.price >= ?";
    $params[] = $minPrice;
}

if ($maxPrice) {
    $query .= " AND p.price <= ?";
    $params[] = $maxPrice;
}

// Apply sorting
switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY p.price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY p.price DESC";
        break;
    case 'newest':
        $query .= " ORDER BY p.created_at DESC";
        break;
    default:
        $query .= " ORDER BY p.product_id DESC";
}

// Execute the query
$products = $db->query($query, $params)->get();

// Get categories for filter dropdown
$categories = $db->query("SELECT * FROM categories ORDER BY category_name")->get();

view("client/products/index.view.php", [
    'products' => $products,
    'categories' => $categories,
    'currentCategory' => $category,
    'minPrice' => $minPrice,
    'maxPrice' => $maxPrice,
    'currentSort' => $sort
]);