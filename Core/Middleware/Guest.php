<?php
namespace Core\Middleware;

use Core\Session;

class Guest
{
    public function handle()
    {
        if (Session::has('user')) {
            $role = Session::get('user')['role'];
            redirect($role === 'admin' || $role === 'superuser' ? '/dashboard' : '/');
        }
    }
}