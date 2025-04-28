<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

function base_path($path = '')
{
    return BASE_PATH . ($path ? '/' . $path : '');
}

function view($path, $attributes = [])
{
    extract($attributes);
    require base_path("views/{$path}");
}

function redirect($path)
{
    header("Location: {$path}");
    exit();
}

function urlIs($value)
{
    return $_SERVER['REQUEST_URI'] === $value;
}

function abort($code = 404)
{
    http_response_code($code);
    require base_path("views/client/{$code}.php");
    die();
}

function dd($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}