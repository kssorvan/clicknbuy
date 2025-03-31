<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => strtoupper($method),
            'middleware' => null
        ];
        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }
    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }
    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }
    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }
    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }
    // public function route($uri, $method)
    // {
    //     $matches = [];
    //     $matchesAddcart = [];
    //     $dynamicRoute = preg_match('/^\/product\/(\d+)$/', $uri, $matches);
    //     $dynamicRouteAddcart = preg_match('/^\/addcart\/(\d+)$/', $uri, $matchesAddcart);
    //     foreach ($this->routes as $route) {
    //         if (($route['uri'] === $uri || ($dynamicRoute && $route['uri'] === '/product/{id}') || ($dynamicRouteAddcart && $route['uri'] === '/addcart/{id}')) && $route['method'] === strtoupper($method)
    //         ) {
    //             Middleware::resolve($route['middleware']);
    //             if ($dynamicRoute) {
    //                 $_GET['id'] = $matches[1];
    //             }
    //             if ($dynamicRouteAddcart) {
    //                 $_GET['id'] = $matchesAddcart[1];
    //             }
    //             return require base_path('Http/controller/' . ltrim($route['controller'], '/'));
    //         }
    //     }

    //     $this->abort();
    // }

//     public function route($uri, $method)
// {
    
//     $dynamicRoutePattern = '/^\/([a-zA-Z0-9_-]+)\/(\d+)$/';
//     $isDynamicRoute = preg_match($dynamicRoutePattern, $uri, $matches);
    
//     foreach ($this->routes as $route) {
        
//         if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
//             Middleware::resolve($route['middleware']);
//             return require base_path('Http/controller/' . ltrim($route['controller'], '/'));
//         }
        
        
//         if ($isDynamicRoute) {
//             $resourceName = $matches[1];
//             $resourceId = $matches[2];
//             $dynamicPattern = '/' . $resourceName . '/{id}';
            
//             if ($route['uri'] === $dynamicPattern && $route['method'] === strtoupper($method)) {
//                 $_GET['id'] = $resourceId;
//                 Middleware::resolve($route['middleware']);
//                 return require base_path('Http/controller/' . ltrim($route['controller'], '/'));
//             }
//         }
//     }

//     $this->abort();
// }
public function route($uri, $method)
{
    // First check for exact matches
    foreach ($this->routes as $route) {
        if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
            Middleware::resolve($route['middleware']);
            return require base_path('Http/controller/' . ltrim($route['controller'], '/'));
        }
    }
    
    // Then check for dynamic routes
    foreach ($this->routes as $route) {
        // Check if route contains a dynamic parameter
        if (strpos($route['uri'], '{') !== false && $route['method'] === strtoupper($method)) {
            $pattern = preg_replace('/{[^}]+}/', '([^/]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                // Extract parameter value
                array_shift($matches);
                
                // Extract parameter name from route
                preg_match('/{([^}]+)}/', $route['uri'], $paramName);
                if (isset($paramName[1])) {
                    $_GET[$paramName[1]] = $matches[0];
                }
                
                Middleware::resolve($route['middleware']);
                return require base_path('Http/controller/' . ltrim($route['controller'], '/'));
            }
        }
    }

    $this->abort();
}



    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;
        return $this;
    }
    protected function abort($code = 404)
    {
        http_response_code($code);

        return require base_path("views/client/{$code}.php");
        die();
    }
//     public function dispatch($uri, $method)
// {
//     // Normalize URI by removing trailing slash
//     $uri = rtrim($uri, '/');
//     error_log("Requested URI: {$uri}, Method: {$method}");
//     foreach ($this->routes as $route) {
//         $routeUri = rtrim($route['uri'], '/');
//         error_log("Checking route: {$routeUri}, Method: {$route['method']}");
//         if ($routeUri === $uri && $route['method'] === $method) {
//             foreach ($route['middleware'] as $middleware) {
//                 $middlewareClass = "Core\\Middleware\\" . ucfirst($middleware);
//                 (new $middlewareClass)->handle();
//             }
//             $controllerPath = __DIR__ . "/../Http/controllers/{$route['controller']}";
//             if (file_exists($controllerPath)) {
//                 require $controllerPath;
//             } else {
//                 error_log("Controller not found: {$controllerPath}");
//                 http_response_code(404);
//                 view('errors/404.view.php');
//                 exit();
//             }
//             return;
//         }
//     }
//     error_log("No route matched for URI: {$uri}");
//     http_response_code(404);
//     view('errors/404.view.php');
//     exit();
// }
}
