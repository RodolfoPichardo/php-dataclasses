<?php

require_once("../src/Dataclass.php");
require_once("../src/ArrayOf.php");

use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;
use Dataclasses\ArrayOf;

class GenericArraysTest extends Dataclass {
    #[ArrayOf("integer")]
    public ArrayOf $arr;
}

class PrimitiveArrays extends TestCase
{
    public function testArray() {
        $arr = [3,1,4,1,5,9,2];
        $class = new GenericArraysTest(['arr' => $arr]);
        $this->assertTrue(isset($class->arr));
        $this->assertSame($arr, $class->arr->getArrayCopy());
    }
}
