<?php
namespace Core;

use Core\App;
use Core\Database;
use Core\Session;

class Authenticator
{
    public function attempt($email, $password)
    {
        $db = App::resolve(Database::class);

        $user = $db->query(
            'SELECT user_id, name, email, password, role, profile_image_url FROM users WHERE email = ? AND is_deleted = FALSE',
            [$email]
        )->find();

        if ($user && password_verify($password, $user['password'])) {
            $this->login([
                'user_id' => $user['user_id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'profile_image_url' => $user['profile_image_url'],
                'role' => $user['role']
            ]);
            return true;
        }

        return false;
    }

    public function login($user)
    {
        Session::put('user', [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'profile_image_url' => $user['profile_image_url'],
            'role' => $user['role']
        ]);
        session_regenerate_id(true);
    }

    public function logout()
    {
        Session::remove('user');
        session_regenerate_id(true);
    }
}