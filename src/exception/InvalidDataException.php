<?php

namespace Dataclasses\exception;

use InvalidArgumentException;
use Throwable;

class InvalidDataException extends InvalidArgumentException
{
    public string $className;
    public string $fieldName;

    function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, string $className = "", string $fieldName = "")
    {
        $this->className = $className;
        $this->fieldName = $fieldName;
        parent::__construct($message, $code, $previous);
    }

    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }
    

}