


<?php
// Http/controller/client/login/index.php
use Core\App;
use Core\Authenticator;
use Core\Database;
use Core\Validator;

$email = $_POST['email-login'] ?? '';
$password = $_POST['password-login'] ?? '';

// Validate the form inputs
$errors = [];

if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password)) {
    $errors['password'] = 'Please provide a valid password.';
}

if (!empty($errors)) {
    // Store errors in session and redirect back
    $_SESSION['errors'] = $errors;
    redirect('/');
    exit();
}

// Attempt to authenticate the user
$authenticator = new Authenticator();
if ($authenticator->attempt($email, $password)) {
    // If successful, redirect to home or previous page
    $redirectTo = $_SESSION['redirect_after_login'] ?? '/';
    unset($_SESSION['redirect_after_login']);
    
    redirect($redirectTo);
} else {
    // If failed, store error and redirect back
    $_SESSION['errors'] = ['login' => 'Invalid credentials.'];
    redirect('/');
}