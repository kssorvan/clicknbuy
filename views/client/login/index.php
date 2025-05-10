<?php
use Core\App;
use Core\Database;
use Core\Session;
use Core\Validator;

$db = App::resolve(Database::class);

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $email = $_POST['email-login'] ?? '';
//     $password = $_POST['password-login'] ?? '';

//     // Validation
//     $errors = [];
//     if (!Validator::email($email)) {
//         $errors['email'] = 'Please provide a valid email address.';
//     }
//     if (!Validator::passwordlength($password)) {
//         $errors['password'] = 'Password must be at least 8 characters long.';
//     } elseif (!Validator::passwordComplexity($password)) {
//         $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
//     }

//     if (!empty($errors)) {
//         Session::flash('errors', $errors);
//         Session::flash('old', ['email-login' => $email]);
//         redirect('/login');
//     }

//     // Authenticate user
//     $user = $db->query("
//         SELECT user_id, name, email, password, role
//         FROM users
//         WHERE email = :email AND is_deleted = FALSE
//     ", ['email' => $email])->find();

//     if (!$user || !password_verify($password, $user['password'])) {
//         Session::flash('errors', ['login' => 'Invalid credentials.']);
//         Session::flash('old', ['email-login' => $email]);
//         redirect('/login');
//     }

//     // Store user in session
//     Session::put('user', [
//         'user_id' => $user['user_id'],
//         'name' => $user['name'],
//         'email' => $user['email'],
//         'role' => $user['role']
//     ]);

//     // Update last login
//     $db->query("UPDATE users SET last_login = NOW() WHERE user_id = ?", [$user['user_id']]);

//     // Merge session cart with database cart
//     if (Session::has('cart')) {
//         foreach (Session::get('cart') as $item) {
//             if (!isset($item['id']) || !isset($item['quantity']) || !is_numeric($item['quantity'])) {
//                 continue;
//             }

//             $product = $db->query(
//                 "SELECT stock FROM products WHERE product_id = ? AND is_deleted = FALSE",
//                 [$item['id']]
//             )->find();

//             if (!$product || $product['stock'] <= 0) {
//                 continue;
//             }

//             $quantity = min($item['quantity'], $product['stock']);

//             try {
//                 $existing = $db->query(
//                     "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?",
//                     [$user['user_id'], $item['id']]
//                 )->find();

//                 if ($existing) {
//                     $newQuantity = $existing['quantity'] + $quantity;
//                     $newQuantity = min($newQuantity, $product['stock']);
//                     $db->query(
//                         "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?",
//                         [$newQuantity, $user['user_id'], $item['id']]
//                     );
//                 } else {
//                     $db->query(
//                         "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)",
//                         [$user['user_id'], $item['id'], $quantity]
//                     );
//                 }
//             } catch (\PDOException $e) {
//                 error_log("Cart merge error: " . $e->getMessage());
//                 Session::flash('error', 'An error occurred while merging your cart. Some items may not have been added.');
//             }
//         }
//         Session::remove('cart');
//     }

//     // Redirect based on role
//     if (in_array($user['role'], ['admin', 'superuser'])) {
//         redirect('/dashboard');
//     } else {
//         $redirectTo = Session::get('redirect_after_login') ?? '/';
//         Session::remove('redirect_after_login');
//         redirect($redirectTo);
//     }
// }

// // Display login form (GET)
// view('client/login/index.php', [
//     'title' => 'Login — clicknbuy',
//     'errors' => Session::getFlash('errors') ?? [],
//     'old' => Session::getFlash('old') ?? []
// ]);





if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email-login'] ?? '';
    $password = $_POST['password-login'] ?? '';

    // Validation
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
        Session::flash('errors', $errors);
        Session::flash('old', ['email-login' => $email]);
        redirect('/login');
    }

    // Authenticate
    $authenticator = new Authenticator();
    if ($authenticator->attempt($email, $password)) {
        // Update last login
        $db->query("UPDATE users SET last_login = NOW() WHERE email = ?", [$email]);

        // Merge session cart
        if (Session::has('cart')) {
            foreach (Session::get('cart') as $item) {
                if (!isset($item['id']) || !isset($item['quantity']) || !is_numeric($item['quantity'])) {
                    continue;
                }

                $product = $db->query(
                    "SELECT stock FROM products WHERE product_id = ? AND is_deleted = FALSE",
                    [$item['id']]
                )->find();

                if (!$product || $product['stock'] <= 0) {
                    continue;
                }

                $quantity = min($item['quantity'], $product['stock']);

                try {
                    $existing = $db->query(
                        "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?",
                        [Session::get('user')['user_id'], $item['id']]
                    )->find();

                    if ($existing) {
                        $newQuantity = $existing['quantity'] + $quantity;
                        $newQuantity = min($newQuantity, $product['stock']);
                        $db->query(
                            "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?",
                            [$newQuantity, Session::get('user')['user_id'], $item['id']]
                        );
                    } else {
                        $db->query(
                            "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)",
                            [Session::get('user')['user_id'], $item['id'], $quantity]
                        );
                    }
                } catch (\PDOException $e) {
                    error_log("Cart merge error: " . $e->getMessage());
                    Session::flash('error', 'An error occurred while merging your cart.');
                }
            }
            Session::remove('cart');
        }

        // Redirect
        if (in_array(Session::get('user')['role'], ['admin', 'superuser'])) {
            redirect('/dashboard');
        } else {
            $redirectTo = Session::get('redirect_after_login') ?? '/';
            Session::remove('redirect_after_login');
            redirect($redirectTo);
        }
    } else {
        Session::flash('errors', ['login' => 'Invalid credentials.']);
        Session::flash('old', ['email-login' => $email]);
        redirect('/login');
    }
}

// Display login form
view('client/login/index.php', [
    'title' => 'Login — clicknbuy',
    'errors' => Session::getFlash('errors') ?? [],
    'old' => Session::getFlash('old') ?? []
]);