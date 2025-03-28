<?php

// Http/controller/client/products/index.php
use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

// Get filter parameters (for future use)
$category = $_GET['category'] ?? null;
$minPrice = $_GET['price_min'] ?? null;
$maxPrice = $_GET['price_max'] ?? null;
$sort = $_GET['sort'] ?? 'default';

// Base query for products
$baseQuery = "SELECT p.*, c.category_name 
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.category_id
              WHERE 1=1";
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

// Fetch Featured Products
$featuredQuery = $baseQuery . " AND p.promotion_type = 'featured'";
$featuredProducts = $db->query($featuredQuery, $params)->get();

// Fetch Big Sale Products
$bigSaleQuery = $baseQuery . " AND p.promotion_type = 'big_sale'";
$bigSaleProducts = $db->query($bigSaleQuery, $params)->get();

// Fetch Weekly Deals
$weeklyDealQuery = $baseQuery . " AND p.promotion_type = 'weekly_deal'";
$weeklyDealProducts = $db->query($weeklyDealQuery, $params)->get();

// Fetch New Arrivals
$newArrivalQuery = $baseQuery . " AND p.promotion_type = 'new_arrival'";
$newArrivalProducts = $db->query($newArrivalQuery, $params)->get();

// Apply sorting to all sections
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

// Append sorting to each query and re-fetch
$featuredProducts = $db->query($featuredQuery . $sortClause, $params)->get();
$bigSaleProducts = $db->query($bigSaleQuery . $sortClause, $params)->get();
$weeklyDealProducts = $db->query($weeklyDealQuery . $sortClause, $params)->get();
$newArrivalProducts = $db->query($newArrivalQuery . $sortClause, $params)->get();

// Get categories for filter dropdown
$categories = $db->query("SELECT * FROM categories ORDER BY category_name")->get();

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