<?php

namespace Dataclasses;

require_once("exception/InvalidDataException.php");

use ArrayIterator;
use ArrayObject;
use Attribute;
use Dataclasses\exception\InvalidDataException;

#[Attribute] class DictOf extends ArrayObject
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

        foreach ($arr as $key => $value) {
            if ($this->isPrimitive()) {
                $newArr[$key] = $this->_parse_primitive($key, $value);
            } else {
                $newArr[$key] = $this->_parse_object($value);
            }
        }

        $this->exchangeArray($newArr);
    }

    public function isPrimitive(): bool
    {
        return in_array($this->className, ["integer", "double", "boolean", "string"]);
    }

    protected function _parse_primitive(int|string $key, mixed $value): mixed
    {
        $type = gettype($value);
        if ($type === $this->className) {
            return $value;
        }
        if ($type === 'integer' && $this->className === 'double') {
            return $value;
        }

        throw new InvalidDataException("ArrayOf($this->className) received an invalid value of type $type on index/key $key",
            className: $this->className, fieldName: $key);
    }

    protected function _parse_object(array $value): object
    {
        return new $this->className($value);
    }
}