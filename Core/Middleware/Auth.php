<?php
namespace Core\Middleware;

class Auth
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            // Store the current URL for redirect after login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $_SESSION['error'] = 'You must be logged in to access this page.';
            header('Location: /login');
            exit();
        }
    }
}