<?php
namespace Core;

class Authenticator
{
    public function attempt($email, $password)
    {
        $db = App::resolve(Database::class);

        $user = $db->query(
            'SELECT * FROM users WHERE email = ? AND is_deleted = FALSE',
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
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'profile_image_url' => $user['profile_image_url'],
            'role' => $user['role']
        ];
        session_regenerate_id(true);
    }

    public function logout()
    {
        $_SESSION['user'] = [];
        session_regenerate_id(true);
        // Optionally destroy the session completely
        // session_destroy();
    }
}