<?php

namespace Http\controller\dashboard\products;

use Core\App;
use Core\Database;
use Core\Validator;
use Core\ValidationException;
use Core\Services\ImageUploadService;

class ProductsController
{
    protected $db;
    protected $imageUploadService;

    public function __construct(Database $db, ImageUploadService $imageUploadService)
    {
        $this->db = $db;
        $this->imageUploadService = $imageUploadService;
    }

    public function index($page = "dashboard")
    {
        $products = $this->db->query("
            SELECT p.*, c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
        ")->get();

        $categories = $this->db->query("SELECT * FROM categories")->get();

        view($page . '/products/index.view.php', [
            'heading' => 'Products',
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
    public function showOneProduct($id)
    {
        $product = $this->db->query("
            SELECT p.*, c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = ?
        ", [$id])->findOrFail();
        
        $relatedProducts = $this->db->query("
            SELECT p.*, c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.category_id = ? AND p.product_id != ?
            LIMIT 4
        ", [$product['category_id'], $id])->get();

        view('client/products/show.view.php', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
    
    public function showOneProductAddCart($id)
    {
        $product = $this->db->query("
            SELECT p.*, c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = ?
        ", [$id])->findOrFail();
        
        $quantity = isset($_GET['quantity']) ? max(1, intval($_GET['quantity'])) : 1;
        if ($quantity > $product['stock']) {
            $_SESSION['cart_message'] = "Sorry, only {$product['stock']} available in stock";
        } else {
            $_SESSION['cart_message'] = "Product added to cart";
        }
        $product['cart_quantity'] = min($quantity, $product['stock']);

        return $product;
    }
    
    public function filterproduct($page)
    {
        $products = $this->db->query("
            SELECT * FROM products
            WHERE stock > 19 AND stock < 40
        ")->get();
        
        view($page . '/index.view.php', [
            'heading' => 'Products',
            'products' => $products,
        ]);
    }

    public function store($request)
    {
        $errors = [];

        if (!Validator::string($request['product-name'], 2, 255)) {
            $errors['product-name'] = 'Product name must be between 2 and 255 characters.';
        }

        if (!Validator::greaterThan((int)$request['product-stock'], -1)) {
            $errors['product-stock'] = 'Stock must be a non-negative number.';
        }

        if (!Validator::greaterThan((float)$request['product-price'], 0)) {
            $errors['product-price'] = 'Price must be greater than zero.';
        }

        $imageUrl = null;
        if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] === UPLOAD_ERR_OK) {
            try {
                $this->imageUploadService->validateImageFile($_FILES['product-image']);
                $uploadResult = $this->imageUploadService->uploadImage($_FILES['product-image']['tmp_name']);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                $errors['product-image'] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            ValidationException::throw($errors, $request);
        }

        $this->db->query("
            INSERT INTO products (name, description, price, stock, category_id, image_url)
            VALUES (?, ?, ?, ?, ?, ?)", [
            $request['product-name'],
            $request['product-description'] ?? '',
            $request['product-price'],
            $request['product-stock'],
            $request['product-category'],
            $imageUrl
        ]);

        redirect('/tbproducts');
    }

    public function update($request)
    {
        $errors = [];
        $id = $request['product_id'];

        $product = $this->db->query("SELECT * FROM products WHERE product_id = ?", [$id])->findOrFail();

        if (!Validator::string($request['product-name-update'], 2, 255)) {
            $errors['product-name-update'] = 'Product name must be between 2 and 255 characters.';
        }

        if (!Validator::greaterThan((int)$request['product-stock-update'], -1)) {
            $errors['product-stock-update'] = 'Stock must be a non-negative number.';
        }

        if (!Validator::greaterThan((float)$request['product-price-update'], 0)) {
            $errors['product-price-update'] = 'Price must be greater than zero.';
        }

        $imageUrl = $product['image_url'];
        if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] === UPLOAD_ERR_OK) {
            try {
                $this->imageUploadService->validateImageFile($_FILES['product-image']);
                $uploadResult = $this->imageUploadService->uploadImage($_FILES['product-image']['tmp_name']);
                $imageUrl = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                $errors['product-image'] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            ValidationException::throw($errors, $request);
        }

        $this->db->query("
            UPDATE products
            SET name = ?,
                description = ?,
                price = ?,
                stock = ?,
                category_id = ?,
                image_url = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE product_id = ?", [
            $request['product-name-update'],
            $request['product-description-update'] ?? '',
            $request['product-price-update'],
            $request['product-stock-update'],
            $request['product-category-update'],
            $imageUrl,
            $id
        ]);

        redirect('/tbproducts');
    }
    
    public function destroy($request)
    {
        $id = $request['product_id'];

        $product = $this->db->query("SELECT * FROM products WHERE product_id = ?", [$id])->findOrFail();

        $this->db->query("DELETE FROM products WHERE product_id = ?", [$id]);

        redirect('/tbproducts');
    }
}