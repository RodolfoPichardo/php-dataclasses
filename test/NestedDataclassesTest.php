<?php

//require_once("../src/Dataclass.php");

use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;

enum Pet
{
    case Dog;
    case Cat;
    case Bird;
    case Fish;
}

class InnerClass extends Dataclass
{
    public int $id;
    public string $name;
    public Pet $pet;
}

class OuterClass extends Dataclass
{
    public string $uid;
    public string $descriptor;
    public InnerClass $innerClass;
}

class Nested extends TestCase
{
    public function testNestedClass(): void
    {
        $class = new OuterClass([
            "uid" => "10dec",
            "descriptor" => "outer class",
            "innerClass" => [
                "id" => 42,
                "name" => "Hello world",
                "pet" => "Dog"
            ]
        ]);

        $this->assertTrue(isset($class->innerClass));
        $this->assertTrue(is_a($class->innerClass, InnerClass::class));
        $this->assertSame($class->innerClass->id, 42);
        $this->assertSame($class->innerClass->name, "Hello world");
        $this->assertSame($class->innerClass->pet, Pet::Dog);
    }
}
