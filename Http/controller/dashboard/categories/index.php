<?php

namespace Http\controller\dashboard\categories;
use Core\App;
use Core\Database;
use Http\controller\dashboard\categories\CategoriesController;

$db = App::resolve(Database::class);
$controller = new CategoriesController($db);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/tbcategories' && $method === 'GET') {
    $controller->index();
} elseif ($uri === '/tbcategories' && $method === 'POST') {
    if (!isset($_POST['category_id'])) {
        $controller->store($_POST);
    }
} elseif ($uri === '/tbcategories/update' && $method === 'POST') {
    $controller->update($_POST);
} elseif ($uri === '/tbcategories/delete' && $method === 'POST') {
    $controller->destroy($_POST);
}
