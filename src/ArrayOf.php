<?php

namespace Dataclasses;

use ArrayIterator;
use ArrayObject;
use Attribute;
use http\Exception\InvalidArgumentException;

#[Attribute] class ArrayOf extends ArrayObject
{
    public string $className;

    public function __construct(string $className)
    {
        parent::__construct([], ArrayObject::STD_PROP_LIST, ArrayIterator::class);
        $this->className = $className;
    }

    public function populate($arr)
    {
        $newArr = [];

        $handler_func = match ($this->className) {
            "integer", "double", "boolean", "string" => '_parse_primitive',
            default => '_parse_object',
        };

        foreach ($arr as $arrayItem) {
            $newArr[] = $this->$handler_func($arrayItem);
        }
        $this->exchangeArray($newArr);
    }

    protected function _parse_primitive(mixed $value): mixed
    {
        $type = gettype($value);
        if ($type === $this->className) {
            return $value;
        }
        if ($type === 'integer' && $this->className === 'double') {
            return $value;
        }

        throw new InvalidArgumentException("ArrayOf($this->className) received an invalid value of type $type");
    }

    protected function _parse_object(array $value): object
    {
        return new $this->className($value);
    }
}