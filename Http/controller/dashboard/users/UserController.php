<?php

namespace Http\controller\dashboard\users;

use Core\Database;
use Core\Validator;
use Core\ValidationException;
use Core\Services\ImageUploadService;

class UserController
{
    protected $db;
    protected $imageUploadService;

    public function __construct(Database $db, ImageUploadService $imageUploadService)
    {
        $this->db = $db;
        $this->imageUploadService = $imageUploadService;
    }

    public function index()
    {

        $users = $this->db->query("
            SELECT u.user_id,u.name,u.email,u.image_url ,r.name as role_name
            FROM users u
            LEFT JOIN roles r ON u.roles_id = r.roles_id
        ")->get();

        view('dashboard/users/index.view.php', [
            "heading" => "Users",
            "users" => $users
        ]);
    }

    public function store($request)
    {
        $errors = [];

        if (!Validator::string($request['user-name'], 2, 255)) {
            $errors['user-name'] = 'User name must be between 2 and 255 characters.';
        }

        if (!Validator::email($request['user-email'])) {
            $errors['user-email'] = 'Invalid email address.';
        }

        if (!Validator::string($request['user-password'], 8, 255)) {
            $errors['user-password'] = 'Password must be between 8 and 255 characters.';
        }

        if (!Validator::conformPassword($request['user-password'], $request['user-password-confirm'])) {
            $errors['user-password'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo  "<script>alert('$error');</script>";
                return redirect('/');
            }
        }

        $this->db->query("
            INSERT INTO users (name, email, password)
            VALUES (?, ?, ?)", [
            $request['user-name'],
            $request['user-email'],
            password_hash($request['user-password'], PASSWORD_BCRYPT)
        ]);

        redirect('/');
    }

    public function update($request)
    {
        $errors = [];
        $id = $request['user_id'];

        $user = $this->db->query("SELECT * FROM users WHERE user_id = ?", [$id])->findOrFail();

        if (!Validator::string($request['user-name-update'], 2, 255)) {
            $errors['user-name-update'] = 'User name must be between 2 and 255 characters.';
        }

        $imageUrl = $user['image_url'];
        if (isset($_FILES['user-image']) && $_FILES['user-image']['error'] === UPLOAD_ERR_OK) {
            try {
                $this->imageUploadService->validateImageFile($_FILES['user-image']);
                $uploadResult = $this->imageUploadService->uploadImage($_FILES['user-image']['tmp_name']);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                $errors['user-image'] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            ValidationException::throw($errors, $request);
        }

        $this->db->query("
            UPDATE users
            SET name = ?,
                image_url = ?
            WHERE user_id = ?", [
            $request['user-name-update'],
            $imageUrl,
            $id
        ]);

        redirect('/user-profile');
    }

    public function destroy($request)
    {
        $id = $request['user_id'];
        $this->db->query("DELETE FROM users WHERE user_id = ?", [$id]);
        redirect('/tbusers');
    }
}
