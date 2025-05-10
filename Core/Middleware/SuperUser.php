<?php
namespace Core\Middleware;

class Superuser
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            redirect('/login');
        }

        $db = App::resolve('Core\Database');
        $hasPermission = $db->query(
            "SELECT COUNT(*) as count 
             FROM permissions 
             WHERE role = ? AND permission_name = 'access_dashboard'",
            [$_SESSION['user']['role']]
        )->find()['count'];

        if (!$hasPermission) {
            $_SESSION['error'] = 'You do not have permission to access this page.';
            redirect('/');
        }
    }
}


