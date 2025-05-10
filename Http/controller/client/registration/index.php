<?php
// Http/controller/client/registration/index.php


use Core\App;
use Core\Database;
use Core\Validator;

$name = $_POST['user-name'] ?? '';
$email = $_POST['user-email'] ?? '';
$password = $_POST['user-password'] ?? '';
$confirmPassword = $_POST['user-password-confirm'] ?? '';

// Validate the form inputs
$errors = [];

if (!Validator::string($name, 3, 100)) {
    $errors['name'] = 'Please provide a name between 3 and 100 characters.';
}

if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::passwordlength($password, 8)) {
    $errors['password'] = 'Password must be at least 8 characters.';
}

if (!Validator::conformPassword($password, $confirmPassword)) {
    $errors['password_confirmation'] = 'Passwords do not match.';
}

$db = App::resolve(Database::class);

// Check if email already exists
$existingUser = $db->query('SELECT * FROM users WHERE email = ?', [$email])->find();

if ($existingUser) {
    $errors['email'] = 'Email already in use. Please login instead.';
}

if (!empty($errors)) {
    // Store errors in session and redirect back
    $_SESSION['errors'] = $errors;
    redirect('/');
    exit();
}

// Create the user account
$db->query('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)', [
    $name,
    $email,
    password_hash($password, PASSWORD_BCRYPT),
    'user'
]);

// Log the user in
$user = $db->query('SELECT * FROM users WHERE email = ?', [$email])->find();

$_SESSION['user'] = [
    'id' => $user['user_id'],
    'email' => $user['email'],
    'name' => $user['name'],
    'image_path' => $user['image_url'] ?? 'asset/images/default-avatar.jpg',
    'role' => $user['role']
];

session_regenerate_id();

redirect('/');