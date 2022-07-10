<?php

require_once("../src/Dataclass.php");
require_once("../src/ArrayOf.php");

use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;
use Dataclasses\ArrayOf;

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



// Self-referencing
class Person extends Dataclass {
    public string $firstName;
    public string $lastName;

    #[ArrayOf(Person::class)]
    public ArrayOf $children;
}

class ArrayOfDataclasses extends TestCase
{
    public function testSimpleArrayOf() {
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
}
