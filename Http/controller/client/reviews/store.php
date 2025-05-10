<?php
// Http/controller/client/reviews/store.php
<?php

use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Validate form data
$productId = $_POST['product_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$title = $_POST['title'] ?? '';
$comment = $_POST['comment'] ?? '';

if (!$productId || !$rating || $rating < 1 || $rating > 5) {
    redirect("/motorcycle/{$productId}");
    exit();
}

$db = App::resolve(Database::class);

// Check if user already reviewed this product
$existingReview = $db->query("
    SELECT * FROM product_reviews
    WHERE product_id = ? AND user_id = ?
", [$productId, $_SESSION['user']['id']])->find();

// Check if user has purchased this product
$verifiedPurchase = $db->query("
    SELECT COUNT(*) as count
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.user_id = ? AND oi.product_id = ?
", [$_SESSION['user']['id'], $productId])->find();

$isVerified = $verifiedPurchase && $verifiedPurchase['count'] > 0;

if ($existingReview) {
    // Update existing review
    $db->query("
        UPDATE product_reviews
        SET rating = ?, title = ?, comment = ?
        WHERE review_id = ?
    ", [
        $rating,
        $title,
        $comment,
        $existingReview['review_id']
    ]);

    $_SESSION['success_message'] = "Your review has been updated.";
} else {
    // Create new review
    $db->query("
        INSERT INTO product_reviews (product_id, user_id, rating, title, comment, verified_purchase)
        VALUES (?, ?, ?, ?, ?, ?)
    ", [
        $productId,
        $_SESSION['user']['id'],
        $rating,
        $title,
        $comment,
        $isVerified ? 1 : 0
    ]);

    $_SESSION['success_message'] = "Thank you for your review!";
}

redirect("/motorcycle/{$productId}");