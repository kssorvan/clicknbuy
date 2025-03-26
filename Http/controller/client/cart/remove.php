
<?php
// Http/controller/client/cart/remove.php
// Get product ID
$productId = $_POST['product_id'] ?? null;

if (!$productId || !isset($_SESSION['cart'])) {
    redirect('/cart');
    exit();
}

// Loop through cart and remove the product
foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $productId) {
        unset($_SESSION['cart'][$key]);
        break;
    }
}

// Reindex array
$_SESSION['cart'] = array_values($_SESSION['cart']);

$_SESSION['cart_message'] = "Item removed from cart";
redirect('/cart');