<?php

use Core\Authenticator;
use Core\ValidationException;
use Core\Validator;

$errors = [];
if (!Validator::email($_POST['email-login'])) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($_POST['password-login'], 8, 255)) {
    $errors['password-login'] = 'Please provide a valid password.';
}

if (!empty($errors)) {
    ValidationException::throw($errors, [
        'email' => $_POST['email-login'],
        'password' => $_POST['password-login']

    ]);
}

$signedIn = (new Authenticator)->attempt(
    $_POST['email-login'],
    $_POST['password-login']
);

if (!$signedIn) {
    ValidationException::error(
        'email',
        'No matching account found for that email address and password.'
    );
}

$_SESSION['user']['role'] === 'admin' ? redirect('/dashboard') : redirect('/');
exit;
