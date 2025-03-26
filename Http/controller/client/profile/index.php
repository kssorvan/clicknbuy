
<?php

// Http/controller/client/profile/index.php

use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

$userId = $_SESSION['user']['id'];
$db = App::resolve(Database::class);

// Get user details
$user = $db->query("
    SELECT * FROM users
    WHERE user_id = ?
", [$userId])->find();

// Get order history
$orders = $db->query("
    SELECT o.*, COUNT(oi.item_id) as item_count
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
", [$userId])->get();

// Get test ride bookings
$testRides = $db->query("
    SELECT tr.*, p.name, p.image_url, mb.brand_name
    FROM test_rides tr
    JOIN products p ON tr.product_id = p.product_id
    LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
    LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
    WHERE tr.user_id = ?
    ORDER BY tr.requested_date DESC, tr.requested_time DESC
", [$userId])->get();

view("client/profile/index.view.php", [
    'user' => $user,
    'orders' => $orders,
    'testRides' => $testRides
]);