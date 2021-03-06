<?php

//require_once("../src/Dataclass.php");
//require_once("../src/ArrayOf.php");
//require_once("../src/DictOf.php");

use Dataclasses\exception\InvalidDataException;
use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;
use Dataclasses\ArrayOf;
use Dataclasses\DictOf;

// Regular
class Course extends Dataclass
{
    public string $code;
    public string $name;
}

class Student extends Dataclass
{
    public int $studentId;
    public string $name;

    #[ArrayOf(Course::class)]
    public ArrayOf $courses;
}

class Student2 extends Dataclass
{
    public int $studentId;
    public string $name;

    #[DictOf(Course::class)]
    public DictOf $courses;
}


// Self-referencing
class Person extends Dataclass
{
    public string $firstName;
    public string $lastName;

    #[ArrayOf(Person::class)]
    public ArrayOf $children;
}

class DictAndArrayOfMixUp extends Dataclass
{
    #[ArrayOf(Course::class)]
    public DictOf $courses;
}

class ArrayOfDataclasses extends TestCase
{
    public function testSimpleArrayOf()
    {
        $student = new Student([
            "studentId" => 54321,
            "name" => "Peter Smith",
            "courses" => [
                [
                    "code" => "csi-101",
                    "name" => "Introduction to Programming"
                ],
                [
                    "code" => "mat-211",
                    "name" => "Linear Algebra"
                ]
            ]
        ]);

        $this->assertTrue(isset($student->courses));
        $this->assertSame(2, count($student->courses));
        $this->assertSame("csi-101", $student->courses[0]->code);
        $this->assertSame("Introduction to Programming", $student->courses[0]->name);
        $this->assertSame("mat-211", $student->courses[1]->code);
        $this->assertSame("Linear Algebra", $student->courses[1]->name);
    }

    public function testSimpleDictOf()
    {
        $student = new Student2([
            "studentId" => 54321,
            "name" => "Peter Smith",
            "courses" => [
                "csi-101" => [
                    "code" => "csi-101",
                    "name" => "Introduction to Programming"
                ],
                "mat-211" => [
                    "code" => "mat-211",
                    "name" => "Linear Algebra"
                ]
            ]
        ]);

        $this->assertTrue(isset($student->courses));
        $this->assertSame(2, count($student->courses));
        $this->assertSame("csi-101", array_keys($student->courses->getArrayCopy())[0]);
        $this->assertSame("csi-101", $student->courses["csi-101"]->code);
        $this->assertSame("Introduction to Programming", $student->courses["csi-101"]->name);
        $this->assertSame("mat-211", array_keys($student->courses->getArrayCopy())[1]);
        $this->assertSame("mat-211", $student->courses["mat-211"]->code);
        $this->assertSame("Linear Algebra", $student->courses["mat-211"]->name);
    }

    public function testSelfReferencingArray()
    {
        $person = new Person([
            "firstName" => "Lily",
            "lastName" => "Potter",
            "children" => [
                [
                    "firstName" => "Harry",
                    "lastName" => "Potter",
                    "children" => []
                ]
            ]
        ]);

        $this->assertSame("Lily", $person->firstName);
        $this->assertSame("Potter", $person->lastName);
        $this->assertSame(1, count($person->children));
        $this->assertSame("Harry", $person->children[0]->firstName);
        $this->assertSame("Potter", $person->children[0]->lastName);
        $this->assertEmpty($person->children[0]->children);

    }

    public function testDictOfAndArrayMixUp()
    {
        $this->expectException(InvalidDataException::class);

        new DictAndArrayOfMixUp(["courses" => []]);
    }

    // TODO Test DictOf with non-associative Array as input
    // TODO Test ArrayOf with associative Array as input
}
