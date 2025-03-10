<?php

namespace Http\controller\dashboard\categories;

use Core\Database;
use Core\Validator;
use Core\ValidationException;

class CategoriesController
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $categories = $this->db->query("SELECT * FROM categories")->get();
        view('dashboard/categories/index.view.php',[
            "heading" => "Categories",
            'categories' => $categories
        ]);

    }

    public function store($request)
    {
        $errors = [];

        if (!Validator::string($request['category-name'], 2, 255)) {
            $errors['category-name'] = 'Category name must be between 2 and 255 characters.';
        }

        if (!empty($errors)) {
            ValidationException::throw($errors, $request);
        }

        $this->db->query("
            INSERT INTO categories (category_name, description)
            VALUES (?, ?)", [
            $request['category-name'],
            $request['category-decription'] ?? ''
        ]);

        redirect('/tbcategories');
    }

    public function update($request)
    {
        $errors = [];
        $id = $request['category_id'];

        if (!Validator::string($request['categories-name-update'], 2, 255)) {
            $errors['categories-name-update'] = 'Category name must be between 2 and 255 characters.';
        }
        if (!empty($errors)) {
            ValidationException::throw($errors, $request);
        }

        $this->db->query("
            UPDATE categories
            SET category_name = ?,
                description = ?
            WHERE category_id = ? ", [
            $request['categories-name-update'],
            $request['categories-decription-update'] ?? '',
            $id
        ]);

        redirect('/tbcategories');
    }

    public function destroy($request)
    {
        $id = $request['category_id'];
        $category = $this->db->query("SELECT * FROM categories WHERE category_id = ?", [$id])->findOrFail();
        $this->db->query("DELETE FROM categories WHERE category_id = ?", [$id]);
        redirect('/tbcategories');
    }
}
