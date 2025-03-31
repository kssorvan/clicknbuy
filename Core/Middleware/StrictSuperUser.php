<?php
namespace Core\Middleware;

class StrictSuperuser
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            redirect('/login');
        }

        if ($_SESSION['user']['role'] !== 'superuser') {
            $_SESSION['error'] = 'You do not have permission to access this page.';
            redirect('/dashboard');
        }
    }
}