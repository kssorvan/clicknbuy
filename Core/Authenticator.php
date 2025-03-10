<?php

namespace Core;

class Authenticator
{
    public function attempt($email, $password)
    {

        $user = (App::resolve(Database::class))->query('SELECT * FROM users WHERE email = :email', ['email' => $email])->find();

        if ($user) {
            dd($user['roles_id']);
            if (password_verify($password, $user['password'])) {

                $this->login([
                    'id' => $user['id'],
                    'email' => $email,
                    'name' => $user['name'],
                    'image_url' => $user['image_url'],
                    'role' => $user['roles_id'] === 1 ? 'admin' : 'customer'

                ]);
                return true;
            }
        }

        return false;
    }


    public function login($user)
    {
        $_SESSION['user'] = [
            'id'=> $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'image_path' => $user['image_url'],
            'role' => $user['role']
        ];
        session_regenerate_id();
    }
    public function logout()
    {
        //Session::destroy();
        $_SESSION['user'] = [];
        // session_destroy();
    }
}
