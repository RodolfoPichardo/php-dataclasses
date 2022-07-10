<?php

require_once("../src/Dataclass.php");

use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;


class GenericArraysTest extends Dataclass {
    public array $arr;
}

class PrimitiveArrays extends TestCase
{
    public function testArray() {
        $arr = [3,1,4,1,5,9,2];
        $class = new GenericArraysTest(['arr' => $arr]);
        $this->assertTrue(isset($class->arr));
        $this->assertSame($arr, $class->arr);
        $this->assertSame('array', gettype($class->arr));
    }
}
