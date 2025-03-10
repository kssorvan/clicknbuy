<?php

namespace Core;

class Container
{
    protected $bindings = [];

    public function bind($key, $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve($key)
    {
        if (!isset($this->bindings[$key])) {
            throw new \Exception("Binding '{$key}' does not exist in container");
        }
        $resolver = $this->bindings[$key];
        return $resolver();
    }
    public function get($name) {
        if (!isset($this->services[$name])) {
            throw new \Exception("Service {$name} not found.");
        }
        return $this->services[$name]();
    }
}
