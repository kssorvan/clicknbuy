<?php
namespace App\Controllers\Dashboard;

use Core\App;
use Cloudinary\Cloudinary;

class ProductsController
{
    protected $db;
    protected $cloudinary;

    public function __construct()
    {
        $this->db = App::get('database');
        $cloudinaryConfig = getCloudinaryConfig();
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudinaryConfig['cloud_name'],
                'api_key'    => $cloudinaryConfig['api_key'],
                'api_secret' => $cloudinaryConfig['api_secret'],
            ],
        ]);
    }

    public function index()
    {
        $products = $this->db->query("
            SELECT p.*, c.category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.category_id 
            WHERE p.is_deleted = FALSE
        ")->fetchAll();
        $categories = $this->db->query("SELECT * FROM categories WHERE is_deleted = FALSE")->fetchAll();

        require base_path('app/views/dashboard/products/index.php');
    }

    public function store()
    {
        $imageUrl = '';
        if (!empty($_FILES['product-image']['name'])) {
            $uploadResult = $this->cloudinary->uploadApi()->upload($_FILES['product-image']['tmp_name']);
            $imageUrl = $uploadResult['secure_url'];
        }

        $stmt = $this->db->prepare("
            INSERT INTO products (name, description, price, stock, category_id, image_url, promotion_type, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $_POST['product-name'],
            $_POST['product-description'],
            $_POST['product-price'],
            $_POST['product-stock'],
            $_POST['product-category'],
            $imageUrl,
            'none' // Default promotion_type
        ]);

        header('Location: /tbproducts');
        exit;
    }

    public function update()
    {
        $imageUrl = $_POST['image_url'] ?? '';
        if (!empty($_FILES['product-image']['name'])) {
            $uploadResult = $this->cloudinary->uploadApi()->upload($_FILES['product-image']['tmp_name']);
            $imageUrl = $uploadResult['secure_url'];
        }

        $stmt = $this->db->prepare("
            UPDATE products 
            SET name = ?, description = ?, price = ?, stock = ?, category_id = ?, image_url = ?, updated_at = NOW()
            WHERE product_id = ?
        ");
        $stmt->execute([
            $_POST['product-name-update'],
            $_POST['product-description-update'],
            $_POST['product-price-update'],
            $_POST['product-stock-update'],
            $_POST['product-category-update'],
            $imageUrl,
            $_POST['product_id']
        ]);

        header('Location: /tbproducts');
        exit;
    }

    public function delete()
    {
        $stmt = $this->db->prepare("UPDATE products SET is_deleted = TRUE WHERE product_id = ?");
        $stmt->execute([$_POST['product_id']]);

        header('Location: /tbproducts');
        exit;
    }
}