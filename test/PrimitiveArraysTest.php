<?php

//require_once("../src/Dataclass.php");
//require_once("../src/ArrayOf.php");

use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;
use Dataclasses\ArrayOf;

class GenericArraysTest extends Dataclass
{
    #[ArrayOf("double")]
    public ArrayOf $arr;
}

class PrimitiveArrays extends TestCase
{
    public function testArray()
    {
        $arr = [3, 1.5, 9, 0.0001, 0, -1, -0.0001];
        $class = new GenericArraysTest(['arr' => $arr]);
        $this->assertTrue(isset($class->arr));
        $this->assertSame($arr, $class->arr->getArrayCopy());
    }
}
