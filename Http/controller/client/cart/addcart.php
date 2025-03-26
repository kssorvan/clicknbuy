
<?php
// Http/controller/client/cart/addcart.php
use Core\App;
use Core\Database;

$id = $_GET['id'] ?? null;
$quantity = isset($_GET['quantity']) ? max(1, (int)$_GET['quantity']) : 1;
$returnTo = $_GET['return_to'] ?? '/product/' . $id;

if (!$id) {
    redirect('/');
    exit();
}

$db = App::resolve(Database::class);

// Get product details
$product = $db->query("SELECT * FROM products WHERE product_id = ?", [$id])->find();

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    redirect('/products');
    exit();
}

// Check stock availability
if ($product['stock'] <= 0) {
    $_SESSION['error'] = "Sorry, this product is out of stock.";
    redirect($returnTo);
    exit();
}

// Initialize cart if needed
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product already in cart
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $id) {
        // Update quantity
        $newQuantity = $item['quantity'] + $quantity;
        
        // Limit to available stock
        if ($newQuantity > $product['stock']) {
            $newQuantity = $product['stock'];
            $_SESSION['notice'] = "Quantity adjusted to maximum available stock.";
        }
        
        $item['quantity'] = $newQuantity;
        $found = true;
        break;
    }
}

// If not in cart, add it
if (!$found) {
    // Limit quantity to available stock
    if ($quantity > $product['stock']) {
        $quantity = $product['stock'];
        $_SESSION['notice'] = "Quantity adjusted to maximum available stock.";
    }
    
    $_SESSION['cart'][] = [
        'id' => $id,
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image_url'],
        'quantity' => $quantity,
        'stock' => $product['stock']
    ];
}

$_SESSION['success'] = "Product added to your cart.";
redirect($returnTo);