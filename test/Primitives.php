<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once("../src/Dataclass.php");
use Dataclasses\Dataclass;

/**
 *
 */
class EmptyClass extends Dataclass {
}

/**
 *
 */
class IntegerClass extends Dataclass {
    public int $num;
}

/**
 *
 */
class StringClass extends Dataclass {
    public string $str;
}

/**
 *
 */
class FloatClass extends Dataclass {
    public float $num;
}

/**
 *
 */
class BooleanClass extends Dataclass {
    public bool $yes_or_no;
}

/**
 *
 */
class ClassWithDefaultValues extends Dataclass {
    public float $num = 2.72;
    public string $str = "Bye world";
    public bool $yes_or_no;
}




final class Primitives extends TestCase
{
    public function testEmpty(): void {
        $class = new EmptyClass([]);
        $str = json_encode($class);

        $this->assertSame("{}", $str);
    }

    public function testInteger(): void {
        $class = new IntegerClass(["num" => 42]);

        $this->assertTrue(isset($class->num), "Integer instance variable is not initialized");
        $this->assertSame(42, $class->num);
    }


    public function testString(): void {
        $class = new StringClass(["str" => "Hello world"]);

        $this->assertTrue(isset($class->str), "String instance variable is not initialized");
        $this->assertSame("Hello world", $class->str);
    }

    public function testFloat(): void {
        $class = new FloatClass(["num" => 3.14]);

        $this->assertTrue(isset($class->num), "Float instance variable is not initialized");
        $this->assertSame(3.14, $class->num);

        $class = new FloatClass(["num" => 3]);

        $this->assertSame(3.0, $class->num);
        $this->assertSame('double', gettype($class->num));
    }

    public function BooleanFloat(): void {
        $class = new BooleanClass(["yes_or_no" => true]);

        $this->assertTrue(isset($class->yes_or_no), "Boolean instance variable is not initialized");
        $this->assertSame(true, $class->yes_or_no);
    }

    public function testDefaultValue(): void {
        $class = new ClassWithDefaultValues(['yes_or_no' => true]);

        $this->assertTrue(isset($class->yes_or_no), "Boolean instance variable is not initialized");
        $this->assertSame(true, $class->yes_or_no);

        $this->assertTrue(isset($class->num), "Boolean instance variable is not initialized");
        $this->assertSame(2.72, $class->num);

        $this->assertTrue(isset($class->str), "Boolean instance variable is not initialized");
        $this->assertSame("Bye world", $class->str);
    }
}