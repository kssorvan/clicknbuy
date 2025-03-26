<?php// Http/controller/client/trade-in/store.php


use Core\App;
use Core\Database;
use Core\Services\ImageUploadService;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Get form data
$brand = $_POST['brand'] ?? '';
$model = $_POST['model'] ?? '';
$year = $_POST['year'] ?? '';
$mileage = $_POST['mileage'] ?? '';
$condition = $_POST['condition'] ?? '';
$description = $_POST['description'] ?? '';

// Validate form data
$errors = [];

if (empty($brand) || empty($model) || empty($year) || empty($mileage) || empty($condition)) {
    $_SESSION['trade_in_errors'] = "Please fill in all required fields.";
    redirect('/trade-in');
    exit();
}

$db = App::resolve(Database::class);
$cloudinary = App::resolve(ImageUploadService::class);
$userId = $_SESSION['user']['id'];

// Handle image uploads
$images = [];
if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            try {
                $uploadResult = $cloudinary->uploadImage($tmpName, [
                    'folder' => 'trade-ins'
                ]);
                $images[] = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                // Log error and continue
            }
        }
    }
}

// Create trade-in request
$db->query("
    INSERT INTO trade_in_requests (user_id, brand, model, year, mileage, `condition`, description, images)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
", [
    $userId,
    $brand,
    $model,
    $year,
    $mileage,
    $condition,
    $description,
    !empty($images) ? json_encode($images) : null
]);

$_SESSION['trade_in_success'] = "Your trade-in request has been submitted. We'll contact you with an evaluation soon.";
redirect('/profile');