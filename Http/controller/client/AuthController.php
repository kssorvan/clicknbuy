<?php
namespace App\Controllers\Client;

use Core\App;
use Core\Database;
use Core\Session;
use Core\Validator;

// class AuthController
// {
//     protected $db;

//     public function __construct()
//     {
//         $this->db = App::resolve(Database::class);
//     }

//     // Display login form (GET /login)
//     public function index()
//     {
//         view('client/login/index.php', [
//             'errors' => Session::getFlash('errors') ?? [],
//             'old' => Session::getFlash('old') ?? []
//         ]);
//     }

//     // Process login (POST /login)
//     public function login()
//     {
//         $email = $_POST['email'] ?? '';
//         $password = $_POST['password'] ?? '';

//         // Validation
//         $errors = [];
//         if (!Validator::email($email)) {
//             $errors['email'] = 'Please provide a valid email address.';
//         }
//         if (!Validator::string($password, 6)) {
//             $errors['password'] = 'Password must be at least 6 characters.';
//         }

//         if (!empty($errors)) {
//             Session::flash('errors', $errors);
//             Session::flash('old', ['email' => $email]);
//             redirect('/login');
//         }

//         // Check credentials
//         $user = $this->db->query("
//             SELECT user_id, name, email, password, role
//             FROM users
//             WHERE email = :email AND is_deleted = FALSE
//         ", ['email' => $email])->find();

//         if (!$user || !password_verify($password, $user['password'])) {
//             Session::flash('errors', ['email' => 'Invalid email or password.']);
//             Session::flash('old', ['email' => $email]);
//             redirect('/login');
//         }

//         // Store user in session
//         Session::put('user', [
//             'user_id' => $user['user_id'],
//             'name' => $user['name'],
//             'email' => $user['email'],
//             'role' => $user['role']
//         ]);

//         // Redirect based on role
//         if ($user['role'] === 'admin' || $user['role'] === 'superuser') {
//             redirect('/dashboard');
//         } else {
//             redirect('/');
//         }
//     }

//     // Logout (POST /logout)
//     public function destroy()
//     {
//         Session::destroy();
//         redirect('/login');
//     }
// }

class AuthController
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    // Display login form (GET /login)
    public function index()
    {
        view('client/login/index.php', [
            'errors' => Session::getFlash('errors') ?? [],
            'old' => Session::getFlash('old') ?? []
        ]);
    }

    // Process login (POST /login)
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validation
        $errors = [];
        if (!Validator::email($email)) {
            $errors['email'] = 'Please provide a valid email address.';
        }
        if (!Validator::string($password, 6)) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', ['email' => $email]);
            redirect('/login');
        }

        // Check credentials
        $user = $this->db->query("
            SELECT user_id, name, email, password, role
            FROM users
            WHERE email = :email AND is_deleted = FALSE
        ", ['email' => $email])->find();

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('errors', ['email' => 'Invalid email or password.']);
            Session::flash('old', ['email' => $email]);
            redirect('/login');
        }

        // Store user in session
        Session::put('user', [
            'user_id' => $user['user_id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);

        // Redirect based on role
        if ($user['role'] === 'admin' || $user['role'] === 'superuser') {
            redirect('/dashboard');
        } else {
            redirect('/');
        }
    }

    // Logout (POST /logout)
    public function destroy()
    {
        Session::destroy();
        redirect('/login');
    }
}