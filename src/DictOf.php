<?php

namespace Dataclasses;

use Attribute;

#[Attribute] class DictOf extends ArrayOf
{
    public function populate($arr)
    {
        $newArr = [];

        $handler_func = match ($this->className) {
            "integer", "double", "boolean", "string" => '_parse_primitive',
            default => '_parse_object',
        }; // FIXME this is unnecessarily unclean

        foreach ($arr as $arrayKey => $arrayItem) {
            $newArr[$arrayKey] = $this->$handler_func($arrayItem);
        }
        $this->exchangeArray($newArr);
    }
}