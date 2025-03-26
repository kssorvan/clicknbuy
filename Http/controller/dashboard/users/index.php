<?php// Http/controller/dashboard/users/index.php


use Core\App;
use Core\Database;

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    abort(403);
}

$db = App::resolve(Database::class);

// Get users
$users = $db->query("
    SELECT u.*, 
           (SELECT COUNT(*) FROM orders WHERE user_id = u.user_id) as order_count,
           (SELECT SUM(total_amount) FROM orders WHERE user_id = u.user_id AND status != 'canceled') as total_spent
    FROM users u
    ORDER BY u.created_at DESC
")->get();

view("dashboard/users/index.view.php", [
    "heading" => "User Management",
    "users" => $users
]);