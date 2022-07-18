<?php

namespace Dataclasses\Utils;

use TypeError;
use ReflectionClass;
use ReflectionException;

function get_property_class(object $obj, string $property): string
{
    try {
        $r = new ReflectionClass($obj::class);
        $prop = $r->getProperty($property);
        return $prop->getType()->getName();
    } catch (ReflectionException $e) {
        throw new TypeError(
            "Unexpected error while trying to get the type of property `$property` on class `" . $obj::class . "`",
            previous: $e
        );
    }
}