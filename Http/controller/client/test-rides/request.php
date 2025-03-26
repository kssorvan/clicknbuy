<?php
// Http/controller/client/test-rides/request.php


use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/motorcycles');
    exit();
}

$db = App::resolve(Database::class);

// Get motorcycle details
$motorcycle = $db->query("
    SELECT p.*, mb.brand_name, ms.model_year, ms.horsepower, ms.weight
    FROM products p
    LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
    LEFT JOIN motorcycle_brands mb ON ms.brand_id = mb.brand_id
    WHERE p.product_id = ?
", [$id])->find();

if (!$motorcycle) {
    abort(404);
}

// Generate available dates (next 14 days)
$availableDates = [];
$currentDate = new DateTime();
for ($i = 1; $i <= 14; $i++) {
    $currentDate->modify('+1 day');
    // Skip Sundays (you can customize this)
    if ($currentDate->format('N') != 7) {
        $availableDates[] = $currentDate->format('Y-m-d');
    }
}

// Available time slots
$availableTimeSlots = [
    '09:00:00' => '9:00 AM',
    '10:00:00' => '10:00 AM',
    '11:00:00' => '11:00 AM',
    '13:00:00' => '1:00 PM',
    '14:00:00' => '2:00 PM',
    '15:00:00' => '3:00 PM',
    '16:00:00' => '4:00 PM'
];

// Get booked slots
$bookedSlots = [];
$bookedTimes = $db->query("
    SELECT requested_date, requested_time
    FROM test_rides
    WHERE product_id = ? AND status != 'canceled'
", [$id])->get();

foreach ($bookedTimes as $booking) {
    $key = $booking['requested_date'] . ' ' . $booking['requested_time'];
    $bookedSlots[$key] = true;
}

view("client/test-rides/request.view.php", [
    'motorcycle' => $motorcycle,
    'availableDates' => $availableDates,
    'availableTimeSlots' => $availableTimeSlots,
    'bookedSlots' => $bookedSlots
]);