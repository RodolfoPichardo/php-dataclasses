<?php

namespace Dataclasses\exception;

use InvalidArgumentException;

class InvalidFieldException extends InvalidArgumentException
{
    readonly public string $className;
    readonly public string $property;

    public function __construct(string $message, string $className, string $property)
    {
        $this->className = $className;
        $this->property = $property;
        parent::__construct($message, 0, null);
    }
}