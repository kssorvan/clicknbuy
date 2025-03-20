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
    public function route($uri, $method)
    {
        $matches = [];
        $matchesAddcart = [];
        $dynamicRoute = preg_match('/^\/product\/(\d+)$/', $uri, $matches);
        $dynamicRouteAddcart = preg_match('/^\/addcart\/(\d+)$/', $uri, $matchesAddcart);
        foreach ($this->routes as $route) {
            if (($route['uri'] === $uri || ($dynamicRoute && $route['uri'] === '/product/{id}') || ($dynamicRouteAddcart && $route['uri'] === '/addcart/{id}')) && $route['method'] === strtoupper($method)
            ) {
                Middleware::resolve($route['middleware']);
                if ($dynamicRoute) {
                    $_GET['id'] = $matches[1];
                }
                if ($dynamicRouteAddcart) {
                    $_GET['id'] = $matchesAddcart[1];
                }
                return require base_path('Http/controller/' . ltrim($route['controller'], '/'));
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
}
