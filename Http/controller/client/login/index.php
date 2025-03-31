<?php
// Http/controllers/client/login/index.php
use Core\App;
use Core\Authenticator;
use Core\Database;
use Core\Validator;

// Resolve dependencies
$db = App::resolve('Core\Database');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle login form submission
    $email = $_POST['email-login'] ?? '';
    $password = $_POST['password-login'] ?? '';

    // Validate the form inputs
    $errors = [];

    if (!Validator::email($email)) {
        $errors['email'] = 'Please provide a valid email address.';
    }

    if (!Validator::passwordlength($password)) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    } elseif (!Validator::passwordComplexity($password)) {
        $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
    }

    if (!empty($errors)) {
        // Store errors in session and redirect back
        $_SESSION['errors'] = $errors;
        redirect('/login');
        exit();
    }

    // Attempt to authenticate the user
    $authenticator = new Authenticator();
    if ($authenticator->attempt($email, $password)) {
        // Update last login timestamp
        $db->query(
            "UPDATE users SET last_login = NOW() WHERE email = ?",
            [$email]
        );

        // Merge session cart with database cart for logged-in user
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                // Validate cart item structure
                if (!isset($item['id']) || !isset($item['quantity']) || !is_numeric($item['quantity'])) {
                    continue;
                }

                // Check if the product still exists and is in stock
                $product = $db->query(
                    "SELECT stock FROM products WHERE product_id = ? AND is_deleted = FALSE",
                    [$item['id']]
                )->find();

                if (!$product || $product['stock'] <= 0) {
                    continue; // Skip invalid or out-of-stock products
                }

                // Adjust quantity if it exceeds stock
                $quantity = min($item['quantity'], $product['stock']);

                try {
                    $existing = $db->query(
                        "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?",
                        [$_SESSION['user']['user_id'], $item['id']]
                    )->find();

                    if ($existing) {
                        // Update quantity
                        $newQuantity = $existing['quantity'] + $quantity;
                        $newQuantity = min($newQuantity, $product['stock']);
                        $db->query(
                            "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?",
                            [$newQuantity, $_SESSION['user']['user_id'], $item['id']]
                        );
                    } else {
                        // Insert new cart item
                        $db->query(
                            "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)",
                            [$_SESSION['user']['user_id'], $item['id'], $quantity]
                        );
                    }
                } catch (\PDOException $e) {
                    error_log("Cart merge error: " . $e->getMessage());
                    $_SESSION['error'] = 'An error occurred while merging your cart. Some items may not have been added.';
                }
            }
            unset($_SESSION['cart']);
        }

        // Redirect based on role
        if (in_array($_SESSION['user']['role'], ['admin', 'superuser'])) {
            redirect('/dashboard');
        } else {
            $redirectTo = $_SESSION['redirect_after_login'] ?? '/';
            unset($_SESSION['redirect_after_login']);
            redirect($redirectTo);
        }
    } else {
        // If authentication fails, store error and redirect back
        $_SESSION['errors'] = ['login' => 'Invalid credentials.'];
        redirect('/login');
    }
} else {
    // Handle GET request (display login form)
    view('client/login/index.view.php', [
        'title' => 'Login — ClicknBuy',
        'errors' => $_SESSION['errors'] ?? [],
    ]);

    // Clear errors from session after displaying
    unset($_SESSION['errors']);
}