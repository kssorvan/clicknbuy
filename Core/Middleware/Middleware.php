<?php

namespace Core\Middleware;

class Middleware
{

    public const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
        'superuser' => SuperUser::class,

    ];

    public static function resolve($middleware)
    {
        if (!$middleware) {
            return;
        }
    
        // If $middleware is an array, loop through each middleware
        if (is_array($middleware)) {
            foreach ($middleware as $m) {
                static::resolve($m); // Recursively resolve each middleware
            }
            return;
        }
    
        // Handle a single middleware (string)
        $middlewareClass = "Core\\Middleware\\" . $middleware;
        if (!class_exists($middlewareClass)) {
            throw new \Exception("Middleware class {$middlewareClass} does not exist.");
        }
    
        $middlewareInstance = new $middlewareClass();
        $middlewareInstance->handle();
    }
}
