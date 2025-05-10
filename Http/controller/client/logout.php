<?php
// Http/controllers/client/logout.php
session_start();

use Core\Authenticator;

$authenticator = new Authenticator();
$authenticator->logout();

$_SESSION['success'] = 'You have been logged out successfully.';
header('Location: /');
exit();