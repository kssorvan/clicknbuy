<?php

function base_path($path)
{
    return __DIR__ . '/' . $path;
}

function view($path, $attributes = [])
{
    extract($attributes);
    require base_path('views/' . $path);
}