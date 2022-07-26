<?php

//require_once("../src/Dataclass.php");
//require_once("../src/ArrayOf.php");

use Dataclasses\exception\InvalidDataException;
use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;
use Dataclasses\ArrayOf;

class ArrayOfFloats extends Dataclass
{
    #[ArrayOf("double")]
    public ArrayOf $arr;
}


class ArrayOfIntegers extends Dataclass
{
    #[ArrayOf("integer")]
    public ArrayOf $arr;
}

class ArrayOfStrings extends Dataclass
{
    #[ArrayOf("string")]
    public ArrayOf $arr;
}

class ArrayOfBooleans extends Dataclass
{
    #[ArrayOf("boolean")]
    public ArrayOf $arr;
}

// This class should not work at all
class BareArray extends Dataclass
{
    public array $arr;
}

class ArrayOfMissingItsRule extends Dataclass
{
    // #[ArrayOf("int")]
    public ArrayOf $num;
}


class PrimitiveArrays extends TestCase
{
    public function testEmptyArray()
    {
        $class = new ArrayOfFloats(['arr' => []]);
        $this->assertTrue(isset($class->arr));
        $this->assertEmpty($class->arr->getArrayCopy());
    }

    public function testMissingArray()
    {
        $this->expectException(InvalidDataException::class);
        $class = new ArrayOfFloats([]);
    }

    public function testArrayOfFloats()
    {
        $arr = [3, 1.5, 9, 0.0001, 0, -1, -0.0001];
        $class = new ArrayOfFloats(['arr' => $arr]);
        $this->assertTrue(isset($class->arr));
        $this->assertSame($arr, $class->arr->getArrayCopy());
    }

    public function testArrayOfIntegers()
    {
        $arr = [1, 4];
        $class = new ArrayOfIntegers(['arr' => $arr]);
        $this->assertTrue(isset($class->arr));
        $this->assertSame($arr, $class->arr->getArrayCopy());
    }

    public function testArrayOfIntegersFail()
    {
        $this->expectException(InvalidDataException::class);
        $arr = [1.2, 4.0];
        new ArrayOfIntegers(['arr' => $arr]);
    }

    public function testArrayOfStrings()
    {
        $arr = ["hello", "hola", "salut"];
        $class = new ArrayOfStrings(['arr' => $arr]);
        $this->assertTrue(isset($class->arr));
        $this->assertSame($arr, $class->arr->getArrayCopy());
    }

    public function testArrayOfStringsFail()
    {
        $this->expectException(InvalidDataException::class);
        $arr = ["hello", 5, "salut"];
        new ArrayOfStrings(['arr' => $arr]);
    }

    public function testArrayOfBooleans()
    {
        $arr = [false, true, false];
        $class = new ArrayOfBooleans(['arr' => $arr]);
        $this->assertTrue(isset($class->arr));
        $this->assertSame($arr, $class->arr->getArrayCopy());
    }

    public function testArrayOfBooleansFail()
    {
        $this->expectException(InvalidDataException::class);
        $arr = [false, 1, 0];
        new ArrayOfBooleans(['arr' => $arr]);
    }

    public function testBareArray()
    {
        $this->expectException(TypeError::class);
        new BareArray(["arr" => 1]);
    }

    public function testBareArray2()
    {
        $this->expectException(InvalidDataException::class);
        new BareArray([]);
    }

    public function testBareArray3()
    {
        $this->expectException(InvalidDataException::class);
        new BareArray(["arr" => []]);
    }


    public function testBareArray4()
    {
        $this->expectException(InvalidDataException::class);
        new BareArray(["arr" => [1]]);
    }

    public function testArrayOfWithoutDataclass()
    {
        $this->expectException(InvalidDataException::class);
        new ArrayOfMissingItsRule(["num" => [1]]);
    }

    public function testArrayOfWithoutDataclass2()
    {
        $this->expectException(InvalidDataException::class);
        new ArrayOfMissingItsRule(["num" => []]);
    }
}
