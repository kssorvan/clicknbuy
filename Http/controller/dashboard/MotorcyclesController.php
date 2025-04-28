<?php
namespace App\Controllers\Dashboard;

use Core\App;
use Core\Database;
use Core\Session;
use Core\Validator;
use Core\Services\ImageUploadService;
use Core\Models\MotorcycleBrand;
use Core\Models\MotorcycleFeature;
use Core\Models\MotorcycleSpecs;

class MotorcyclesController
{
    protected $db;
    protected $imageService;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
        $this->imageService = App::resolve(ImageUploadService::class);
    }

    public function index()
    {
        $motorcycles = $this->db->query("
            SELECT p.product_id, p.name, p.price, p.stock, p.image_url, p.is_featured,
                   mb.brand_name, c.category_name, ms.model_year, ms.condition
            FROM products p
            LEFT JOIN motorcycle_brands mb ON p.brand_id = mb.brand_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
            WHERE p.is_deleted = FALSE
            ORDER BY p.created_at DESC
        ")->get();

        view('dashboard/motorcycles/index.php', [
            'motorcycles' => $motorcycles
        ]);
    }

    public function create()
    {
        $brands = (new MotorcycleBrand())->all();
        $categories = $this->db->query("SELECT * FROM categories WHERE is_deleted = FALSE")->get();
        $financingOptions = $this->db->query("SELECT * FROM financing_options WHERE is_deleted = FALSE")->get();

        view('dashboard/motorcycles/create.php', [
            'brands' => $brands,
            'categories' => $categories,
            'financingOptions' => $financingOptions,
            'errors' => Session::get('errors', []),
            'old' => Session::get('old', [])
        ]);
    }

    public function store()
    {
        // Validate inputs
        $errors = [];
        if (!Validator::string($_POST['motorcycle-name'], 1, 255)) {
            $errors['motorcycle-name'] = 'Motorcycle name is required and must be less than 255 characters.';
        }
        if (!Validator::greaterThan($_POST['motorcycle-price'] ?? 0, 0)) {
            $errors['motorcycle-price'] = 'Price must be greater than 0.';
        }
        if (!Validator::greaterThan($_POST['motorcycle-stock'] ?? 0, 0)) {
            $errors['motorcycle-stock'] = 'Stock must be greater than 0.';
        }
        if (empty($_POST['motorcycle-brand'])) {
            $errors['motorcycle-brand'] = 'Brand is required.';
        }
        if (empty($_POST['motorcycle-category'])) {
            $errors['motorcycle-category'] = 'Category is required.';
        }
        if (empty($_POST['motorcycle-condition'])) {
            $errors['motorcycle-condition'] = 'Condition is required.';
        }
        if (!isset($_FILES['motorcycle-image']) || $_FILES['motorcycle-image']['error'] !== UPLOAD_ERR_OK) {
            $errors['motorcycle-image'] = 'Primary image is required.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $_POST);
            redirect('/motorcycles/create');
        }

        // Handle primary image upload
        $imageUrl = null;
        $this->imageService->validateImageFile($_FILES['motorcycle-image']);
        $uploadResult = $this->imageService->uploadImage($_FILES['motorcycle-image']['tmp_name']);
        $imageUrl = $uploadResult['secure_url'];

        // Begin transaction
        $this->db->connection->beginTransaction();

        try {
            // Insert product
            $this->db->query("
                INSERT INTO products (name, description, price, stock, category_id, brand_id, image_url, is_featured)
                VALUES (:name, :description, :price, :stock, :category_id, :brand_id, :image_url, :is_featured)
            ", [
                'name' => $_POST['motorcycle-name'],
                'description' => $_POST['motorcycle-description'] ?? null,
                'price' => $_POST['motorcycle-price'],
                'stock' => $_POST['motorcycle-stock'],
                'category_id' => $_POST['motorcycle-category'],
                'brand_id' => $_POST['motorcycle-brand'],
                'image_url' => $imageUrl,
                'is_featured' => isset($_POST['motorcycle-featured']) ? 1 : 0
            ]);

            $productId = $this->db->connection->lastInsertId();

            // Insert specs
            (new MotorcycleSpecs())->create([
                'product_id' => $productId,
                'brand_id' => $_POST['motorcycle-brand'],
                'model_year' => $_POST['motorcycle-year'],
                'engine_type' => $_POST['motorcycle-engine-type'] ?? null,
                'engine_displacement' => $_POST['motorcycle-displacement'] ?? null,
                'horsepower' => $_POST['motorcycle-horsepower'] ?? null,
                'torque' => $_POST['motorcycle-torque'] ?? null,
                'transmission_type' => $_POST['motorcycle-transmission'] ?? null,
                'gear_count' => $_POST['motorcycle-gears'] ?? null,
                'fuel_capacity' => $_POST['motorcycle-fuel-capacity'] ?? null,
                'fuel_economy' => $_POST['motorcycle-fuel-economy'] ?? null,
                'seat_height' => $_POST['motorcycle-seat-height'] ?? null,
                'weight' => $_POST['motorcycle-weight'] ?? null,
                'vin' => $_POST['motorcycle-vin'] ?? null,
                'mileage' => $_POST['motorcycle-mileage'] ?? 0,
                'condition' => $_POST['motorcycle-condition'],
                'color' => $_POST['motorcycle-color'] ?? null
            ]);

            // Insert features
            $features = [];
            if (isset($_POST['feature-name'])) {
                foreach ($_POST['feature-name'] as $index => $name) {
                    if (!empty($name)) {
                        $features[] = [
                            'name' => $name,
                            'description' => $_POST['feature-description'][$index] ?? null
                        ];
                    }
                }
                (new MotorcycleFeature())->bulkCreate($productId, $features);
            }

            // Insert additional images
            if (isset($_FILES['motorcycle-additional-images']['tmp_name'])) {
                foreach ($_FILES['motorcycle-additional-images']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['motorcycle-additional-images']['error'][$index] === UPLOAD_ERR_OK) {
                        $this->imageService->validateImageFile([
                            'name' => $_FILES['motorcycle-additional-images']['name'][$index],
                            'type' => $_FILES['motorcycle-additional-images']['type'][$index],
                            'tmp_name' => $tmpName,
                            'error' => $_FILES['motorcycle-additional-images']['error'][$index],
                            'size' => $_FILES['motorcycle-additional-images']['size'][$index]
                        ]);
                        $uploadResult = $this->imageService->uploadImage($tmpName);
                        $this->db->query("
                            INSERT INTO product_images (product_id, image_url)
                            VALUES (:product_id, :image_url)
                        ", [
                            'product_id' => $productId,
                            'image_url' => $uploadResult['secure_url']
                        ]);
                    }
                }
            }

            // Insert financing options
            if (isset($_POST['financing-options'])) {
                foreach ($_POST['financing-options'] as $optionId) {
                    $this->db->query("
                        INSERT INTO product_financing (product_id, financing_option_id)
                        VALUES (:product_id, :financing_option_id)
                    ", [
                        'product_id' => $productId,
                        'financing_option_id' => $optionId
                    ]);
                }
            }

            $this->db->connection->commit();
            Session::flash('success', 'Motorcycle created successfully.');
            redirect('/motorcycles/admin');
        } catch (\Exception $e) {
            $this->db->connection->rollBack();
            Session::flash('error', 'Failed to create motorcycle: ' . $e->getMessage());
            redirect('/motorcycles/create');
        }
    }

    public function edit()
    {
        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            abort(404);
        }

        $product = $this->db->query("
            SELECT p.*, ms.*, GROUP_CONCAT(pf.financing_option_id) as financing_options
            FROM products p
            LEFT JOIN motorcycle_specs ms ON p.product_id = ms.product_id
            LEFT JOIN product_financing pf ON p.product_id = pf.product_id
            WHERE p.product_id = :product_id AND p.is_deleted = FALSE
            GROUP BY p.product_id
        ", ['product_id' => $productId])->findOrFail();

        $brands = (new MotorcycleBrand())->all();
        $categories = $this->db->query("SELECT * FROM categories WHERE is_deleted = FALSE")->get();
        $financingOptions = $this->db->query("SELECT * FROM financing_options WHERE is_deleted = FALSE")->get();
        $features = (new MotorcycleFeature())->findByProductId($productId);
        $additionalImages = $this->db->query("
            SELECT * FROM product_images WHERE product_id = :product_id
        ", ['product_id' => $productId])->get();

        view('dashboard/motorcycles/edit.php', [
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories,
            'financingOptions' => $financingOptions,
            'features' => $features,
            'additionalImages' => $additionalImages,
            'errors' => Session::get('errors', []),
            'old' => Session::get('old', [])
        ]);
    }

    public function update()
    {
        $productId = $_POST['product_id'] ?? null;
        if (!$productId) {
            abort(404);
        }

        // Validate inputs
        $errors = [];
        if (!Validator::string($_POST['motorcycle-name'], 1, 255)) {
            $errors['motorcycle-name'] = 'Motorcycle name is required and must be less than 255 characters.';
        }
        if (!Validator::greaterThan($_POST['motorcycle-price'] ?? 0, 0)) {
            $errors['motorcycle-price'] = 'Price must be greater than 0.';
        }
        if (!Validator::greaterThan($_POST['motorcycle-stock'] ?? 0, 0)) {
            $errors['motorcycle-stock'] = 'Stock must be greater than 0.';
        }
        if (empty($_POST['motorcycle-brand'])) {
            $errors['motorcycle-brand'] = 'Brand is required.';
        }
        if (empty($_POST['motorcycle-category'])) {
            $errors['motorcycle-category'] = 'Category is required.';
        }
        if (empty($_POST['motorcycle-condition'])) {
            $errors['motorcycle-condition'] = 'Condition is required.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $_POST);
            redirect("/motorcycles/edit/{$productId}");
        }

        // Begin transaction
        $this->db->connection->beginTransaction();

        try {
            // Handle primary image upload (if provided)
            $imageUrl = $_POST['existing-image-url'] ?? null;
            if (isset($_FILES['motorcycle-image']) && $_FILES['motorcycle-image']['error'] === UPLOAD_ERR_OK) {
                $this->imageService->validateImageFile($_FILES['motorcycle-image']);
                $uploadResult = $this->imageService->uploadImage($_FILES['motorcycle-image']['tmp_name']);
                $imageUrl = $uploadResult['secure_url'];
            }

            // Update product
            $this->db->query("
                UPDATE products SET
                    name = :name,
                    description = :description,
                    price = :price,
                    stock = :stock,
                    category_id = :category_id,
                    brand_id = :brand_id,
                    image_url = :image_url,
                    is_featured = :is_featured,
                    updated_at = CURRENT_TIMESTAMP
                WHERE product_id = :product_id
            ", [
                'name' => $_POST['motorcycle-name'],
                'description' => $_POST['motorcycle-description'] ?? null,
                'price' => $_POST['motorcycle-price'],
                'stock' => $_POST['motorcycle-stock'],
                'category_id' => $_POST['motorcycle-category'],
                'brand_id' => $_POST['motorcycle-brand'],
                'image_url' => $imageUrl,
                'is_featured' => isset($_POST['motorcycle-featured']) ? 1 : 0,
                'product_id' => $productId
            ]);

            // Update specs
            $spec = $this->db->query("SELECT spec_id FROM motorcycle_specs WHERE product_id = :product_id", 
                ['product_id' => $productId])->findOrFail();
            
            (new MotorcycleSpecs())->update($spec['spec_id'], [
                'brand_id' => $_POST['motorcycle-brand'],
                'model_year' => $_POST['motorcycle-year'],
                'engine_type' => $_POST['motorcycle-engine-type'] ?? null,
                'engine_displacement' => $_POST['motorcycle-displacement'] ?? null,
                'horsepower' => $_POST['motorcycle-horsepower'] ?? null,
                'torque' => $_POST['motorcycle-torque'] ?? null,
                'transmission_type' => $_POST['motorcycle-transmission'] ?? null,
                'gear_count' => $_POST['motorcycle-gears'] ?? null,
                'fuel_capacity' => $_POST['motorcycle-fuel-capacity'] ?? null,
                'fuel_economy' => $_POST['motorcycle-fuel-economy'] ?? null,
                'seat_height' => $_POST['motorcycle-seat-height'] ?? null,
                'weight' => $_POST['motorcycle-weight'] ?? null,
                'vin' => $_POST['motorcycle-vin'] ?? null,
                'mileage' => $_POST['motorcycle-mileage'] ?? 0,
                'condition' => $_POST['motorcycle-condition'],
                'color' => $_POST['motorcycle-color'] ?? null
            ]);

            // Update features
            $features = [];
            if (isset($_POST['feature-name'])) {
                foreach ($_POST['feature-name'] as $index => $name) {
                    if (!empty($name)) {
                        $features[] = [
                            'name' => $name,
                            'description' => $_POST['feature-description'][$index] ?? null
                        ];
                    }
                }
                (new MotorcycleFeature())->bulkCreate($productId, $features);
            }

            // Update additional images
            if (isset($_FILES['motorcycle-additional-images']['tmp_name'])) {
                foreach ($_FILES['motorcycle-additional-images']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['motorcycle-additional-images']['error'][$index] === UPLOAD_ERR_OK) {
                        $this->imageService->validateImageFile([
                            'name' => $_FILES['motorcycle-additional-images']['name'][$index],
                            'type' => $_FILES['motorcycle-additional-images']['type'][$index],
                            'tmp_name' => $tmpName,
                            'error' => $_FILES['motorcycle-additional-images']['error'][$index],
                            'size' => $_FILES['motorcycle-additional-images']['size'][$index]
                        ]);
                        $uploadResult = $this->imageService->uploadImage($tmpName);
                        $this->db->query("
                            INSERT INTO product_images (product_id, image_url)
                            VALUES (:product_id, :image_url)
                        ", [
                            'product_id' => $productId,
                            'image_url' => $uploadResult['secure_url']
                        ]);
                    }
                }
            }

            // Update financing options
            $this->db->query("DELETE FROM product_financing WHERE product_id = :product_id", 
                ['product_id' => $productId]);
            if (isset($_POST['financing-options'])) {
                foreach ($_POST['financing-options'] as $optionId) {
                    $this->db->query("
                        INSERT INTO product_financing (product_id, financing_option_id)
                        VALUES (:product_id, :financing_option_id)
                    ", [
                        'product_id' => $productId,
                        'financing_option_id' => $optionId
                    ]);
                }
            }

            $this->db->connection->commit();
            Session::flash('success', 'Motorcycle updated successfully.');
            redirect('/motorcycles/admin');
        } catch (\Exception $e) {
            $this->db->connection->rollBack();
            Session::flash('error', 'Failed to update motorcycle: ' . $e->getMessage());
            redirect("/motorcycles/edit/{$productId}");
        }
    }

    public function delete()
    {
        $productId = $_POST['product_id'] ?? null;
        if (!$productId) {
            abort(404);
        }

        $this->db->query("
            UPDATE products SET is_deleted = TRUE, updated_at = CURRENT_TIMESTAMP
            WHERE product_id = :product_id
        ", ['product_id' => $productId]);

        Session::flash('success', 'Motorcycle deleted successfully.');
        redirect('/motorcycles/admin');
    }
}