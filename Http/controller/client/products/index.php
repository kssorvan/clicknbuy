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

// Fetch products for each section
$featuredQuery = $baseQuery . " AND p.promotion_type = 'featured'";
$bigSaleQuery = $baseQuery . " AND p.promotion_type = 'big_sale'";
$weeklyDealQuery = $baseQuery . " AND p.promotion_type = 'weekly_deal'";
$newArrivalQuery = $baseQuery . " AND p.promotion_type = 'new_arrival'";

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
        $sortClause = " ORDER BY p.product_id DESC";
}

// Fetch products
$featuredProducts = $db->query($featuredQuery . $sortClause, $params)->get();
$bigSaleProducts = $db->query($bigSaleQuery . $sortClause, $params)->get();
$weeklyDealProducts = $db->query($weeklyDealQuery . $sortClause, $params)->get();
$newArrivalProducts = $db->query($newArrivalQuery . $sortClause, $params)->get();

// Get categories for filter dropdown
$categories = $db->query("SELECT * FROM categories WHERE is_deleted = FALSE ORDER BY category_name")->get();

view("client/products/index.view.php", [
    'featuredProducts' => $featuredProducts,
    'bigSaleProducts' => $bigSaleProducts,
    'weeklyDealProducts' => $weeklyDealProducts,
    'newArrivalProducts' => $newArrivalProducts,
    'categories' => $categories,
    'currentCategory' => $category,
    'minPrice' => $minPrice,
    'maxPrice' => $maxPrice,
    'currentSort' => $sort
]);