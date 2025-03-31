<?php
// Http/controllers/dashboard/index.php
use Core\App;

$db = App::resolve('Core\Database');

// Earnings (Monthly) - Last 30 days
$monthlyEarnings = $db->query(
    "SELECT COALESCE(SUM(total_amount), 0) as total 
     FROM orders 
     WHERE payment_status = 'paid' 
     AND created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 DAY)"
)->find()['total'];

// Earnings (Annual) - Last 365 days
$annualEarnings = $db->query(
    "SELECT COALESCE(SUM(total_amount), 0) as total 
     FROM orders 
     WHERE payment_status = 'paid' 
     AND created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 365 DAY)"
)->find()['total'];

// Tasks - Percentage of completed test rides
$totalTestRides = $db->query("SELECT COUNT(*) as count FROM test_rides")->find()['count'];
$completedTestRides = $db->query("SELECT COUNT(*) as count FROM test_rides WHERE status = 'completed'")->find()['count'];
$taskProgress = $totalTestRides > 0 ? round(($completedTestRides / $totalTestRides) * 100) : 0;

// Pending Requests - Pending test rides
$pendingRequests = $db->query("SELECT COUNT(*) as count FROM test_rides WHERE status = 'pending'")->find()['count'];

// Recent Orders - Latest 3 orders
$recentOrders = $db->query(
    "SELECT o.order_id, u.name as customer, o.total_amount, o.status 
     FROM orders o 
     JOIN users u ON o.user_id = u.user_id 
     WHERE o.is_deleted = FALSE 
     ORDER BY o.created_at DESC 
     LIMIT 3"
)->get();

// Recent Test Rides - Latest 3 test rides
$recentTestRides = $db->query(
    "SELECT u.name as customer, p.name as model, tr.requested_date, tr.requested_time, tr.status 
     FROM test_rides tr 
     JOIN users u ON tr.user_id = u.user_id 
     JOIN products p ON tr.product_id = p.product_id 
     ORDER BY tr.created_at DESC 
     LIMIT 3"
)->get();

// Earnings Overview - Monthly earnings for the past 12 months
$monthlyEarningsData = $db->query(
    "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COALESCE(SUM(total_amount), 0) as total 
     FROM orders 
     WHERE payment_status = 'paid' 
     AND created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 12 MONTH) 
     GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
     ORDER BY month ASC"
)->get();

// Format the data for Chart.js
$earningsLabels = [];
$earningsData = [];
$monthNames = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
];
$earningsMap = array_fill(0, 12, 0); // Initialize 12 months with 0

foreach ($monthlyEarningsData as $entry) {
    $date = new DateTime($entry['month']);
    $monthIndex = (int)$date->format('n') - 1; // 0-11
    $earningsMap[$monthIndex] = (float)$entry['total'];
}

for ($i = 0; $i < 12; $i++) {
    $monthDate = (new DateTime())->modify("-" . (11 - $i) . " months");
    $earningsLabels[] = $monthNames[$monthDate->format('n') - 1] . " " . $monthDate->format('Y');
    $earningsData[] = $earningsMap[$i];
}

// Revenue Sources - Breakdown by payment method
$revenueSources = $db->query(
    "SELECT payment_method, COUNT(*) as count 
     FROM orders 
     WHERE payment_status = 'paid' 
     GROUP BY payment_method"
)->get();

$revenueLabels = [];
$revenueData = [];
foreach ($revenueSources as $source) {
    $revenueLabels[] = $source['payment_method'];
    $revenueData[] = $source['count'];
}

view('dashboard/index.view.php', [
    'title' => 'Admin Dashboard — ClicknBuy',
    'monthlyEarnings' => $monthlyEarnings,
    'annualEarnings' => $annualEarnings,
    'taskProgress' => $taskProgress,
    'pendingRequests' => $pendingRequests,
    'recentOrders' => $recentOrders,
    'recentTestRides' => $recentTestRides,
    'earningsLabels' => json_encode($earningsLabels),
    'earningsData' => json_encode($earningsData),
    'revenueLabels' => json_encode($revenueLabels),
    'revenueData' => json_encode($revenueData),
]);