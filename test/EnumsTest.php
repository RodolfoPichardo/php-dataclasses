<?php

require_once("../src/Dataclass.php");

use PHPUnit\Framework\TestCase;
use Dataclasses\Dataclass;

enum Suit
{
    case Hearts;
    case Diamonds;
    case Clubs;
    case Spades;
}

enum Color: int
{
    case Red = 1;
    case Green = 2;
    case Blue = 3;
}

class BasicEnumTest extends Dataclass
{
    public Suit $suit;
}

class BackedEnumTest extends Dataclass
{
    public Color $color;
}

class Enums extends TestCase
{
    public function testSimpleEnum(): void {
        $class = new BasicEnumTest(['suit' => 'Clubs']);
        $this->assertTrue(isset($class->suit));
        $this->assertSame(Suit::Clubs, $class->suit);
        $this->assertSame('object', gettype($class->suit));
    }

    public function testBackedEnum(): void {
        $class = new BackedEnumTest(['color' => 1]); // Red
        $this->assertTrue(isset($class->color));
        $this->assertSame(Color::Red, $class->color);
        $this->assertSame('object', gettype($class->color));
    }
}
