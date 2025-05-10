
<?php
// Http/controller/client/motorcycles/index.php
//<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

// Get filter parameters
$brand = $_GET['brand'] ?? null;
$category = $_GET['category'] ?? null;
$minPrice = $_GET['price_min'] ?? null;
$maxPrice = $_GET['price_max'] ?? null;
$minYear = $_GET['year_min'] ?? null;
$maxYear = $_GET['year_max'] ?? null;
$condition = $_GET['condition'] ?? null;

// Build the query
$query = "
    SELECT p.*, c.category_name, ms.model_year, ms.engine_displacement, ms.horsepower, 
           ms.mileage, ms.transmission_type, ms.condition, mb.brand_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
    LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
    WHERE 1=1
";
$params = [];

// Apply filters
if ($brand) {
    $query .= " AND mb.brand_id = ?";
    $params[] = $brand;
}

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

if ($minYear) {
    $query .= " AND ms.model_year >= ?";
    $params[] = $minYear;
}

if ($maxYear) {
    $query .= " AND ms.model_year <= ?";
    $params[] = $maxYear;
}

if ($condition) {
    $query .= " AND ms.condition = ?";
    $params[] = $condition;
}

$query .= " ORDER BY p.featured DESC, p.created_at DESC";

// Execute the query
$motorcycles = $db->query($query, $params)->get();

// Get brands and categories for filter dropdowns
$brands = $db->query("SELECT * FROM motorcycle_brands ORDER BY brand_name")->get();
$categories = $db->query("SELECT * FROM categories ORDER BY category_name")->get();

view("client/motorcycles/index.view.php", [
    'motorcycles' => $motorcycles,
    'brands' => $brands,
    'categories' => $categories
]);