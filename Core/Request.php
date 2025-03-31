<?php

// In Core/Request.php
namespace Core;

class Request
{
    public static function uri()
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Return '/' for the home page instead of an empty string
    return $uri === '/' ? '/' : trim($uri, '/');
}

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    // Add other methods as needed
}