<?php// Http/controller/client/payment/index.php


// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Check if there's a pending order
if (!isset($_SESSION['pending_order'])) {
    redirect('/cart');
    exit();
}

$pendingOrder = $_SESSION['pending_order'];

view("client/payment/index.view.php", [
    'order' => $pendingOrder
]);