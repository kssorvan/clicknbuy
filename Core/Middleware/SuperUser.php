<?php

namespace Core\Middleware;

class SuperUser
{
    public function handle()
    {
        if ($_SESSION['user']['role'] !== 'admin' ?? false) {
            header('Location: /');
            exit();
        }
    }
}
