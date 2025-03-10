<?php

namespace Core;

class ValidationException extends \Exception
{
    public readonly array $errors;
    public readonly array $old;

    public static function throw($errors, $old)
    {
        $instance = new static('The form failed to validate.');

        $instance->errors = $errors;
        $instance->old = $old;

        throw $instance;
    }
    public static function error($field, $message)
    {
        $instance = new static('The form failed to validate.');
        $instance->errors = [$field => $message];
        return $instance;
    }
}
