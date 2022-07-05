<?php

namespace Dataclasses\Utils;

use TypeError;

function is_assoc(array $arr): bool
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, len($arr) - 1);
}

function len(array $arr): int
{
    return count($arr);
}

function array_get_last(array $arr) {
    return $arr[len($arr) -1];
}

// TODO: This is hacky
// This is a function to get the declared class of instance variables before they are initialized.
function get_object_var_class($obj, $property): string
{
    try {
        $obj->{$property} = '';
    } catch(TypeError $e) {
        $full_classname = explode(' ', $e->getMessage());
        $classname = array_get_last($full_classname);
        if(str_starts_with($classname, '?')) {
            $classname = substr($classname, 1);
        }

        return $classname;
    }

    throw new TypeError("Invalid Object type for Property ${property}");
}

function validate_field(\ReflectionProperty $reflection, $value) {
    $attributes = $reflection->getAttributes();

    foreach ($attributes as $attribute) {
        $validator = $attribute->newInstance();
        if(method_exists($validator, 'validate')) {
            $validator->validate($value);
        }
    }
}

function last_word(string $sentence): string {
    $last_word_start = strrpos($sentence, ' ') + 1; // +1 so we don't include the space in our result
    return substr($sentence, $last_word_start); // $last_word = PHP.
}