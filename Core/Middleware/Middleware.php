<?php
namespace Core\Middleware;

class Middleware
{
    public const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
        'admin' => Admin::class,
        'strict_superuser' => StrictSuperuser::class,
    ];

    public static function resolve($middleware)
    {
        if (!$middleware) {
            return;
        }

        if (is_array($middleware)) {
            foreach ($middleware as $m) {
                static::resolve($m);
            }
            return;
        }

        if (!isset(self::MAP[$middleware])) {
            throw new \Exception("Middleware '{$middleware}' not found.");
        }

        $middlewareClass = self::MAP[$middleware];
        if (!class_exists($middlewareClass)) {
            throw new \Exception("Middleware class {$middlewareClass} does not exist.");
        }

        $middlewareInstance = new $middlewareClass();
        $middlewareInstance->handle();
    }
}