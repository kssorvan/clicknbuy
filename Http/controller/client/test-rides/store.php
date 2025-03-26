<?php
// Http/controller/client/test-rides/store.php


use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Validate form data
$productId = $_POST['product_id'] ?? null;
$date = $_POST['date'] ?? null;
$time = $_POST['time'] ?? null;
$notes = $_POST['notes'] ?? '';

if (!$productId || !$date || !$time) {
    redirect('/motorcycles');
    exit();
}

$db = App::resolve(Database::class);

// Check if the slot is already booked
$existing = $db->query("
    SELECT * FROM test_rides
    WHERE product_id = ? AND requested_date = ? AND requested_time = ? AND status != 'canceled'
", [$productId, $date, $time])->find();

if ($existing) {
    $_SESSION['error_message'] = "Sorry, this time slot is no longer available. Please select another time.";
    redirect("/test-ride/request/{$productId}");
    exit();
}

// Create test ride request
$db->query("
    INSERT INTO test_rides (product_id, user_id, requested_date, requested_time, notes)
    VALUES (?, ?, ?, ?, ?)
", [
    $productId,
    $_SESSION['user']['id'],
    $date,
    $time,
    $notes
]);

$_SESSION['success_message'] = "Your test ride request has been submitted. We'll contact you to confirm your appointment.";
redirect("/motorcycle/{$productId}");