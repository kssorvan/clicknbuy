<?php
namespace Core\Middleware;

use Core\Session;

class StrictSuperuser
{
    public function handle()
    {
        if (!Session::has('user')) {
            Session::put('redirect_after_login', $_SERVER['REQUEST_URI']);
            redirect('/login');
        }

        if (Session::get('user')['role'] !== 'superuser') {
            Session::put('error', 'You do not have permission to access this page.');
            redirect('/dashboard');
        }
    }
}