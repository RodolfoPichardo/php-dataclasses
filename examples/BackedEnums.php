<?php

require_once("../src/Dataclass.php");

use Dataclasses\Dataclass;

enum Language: string
{
    case English = 'en';
    case Spanish = 'es';
    case French = 'fr';
}

class Book extends Dataclass
{
    public string $title;
    public string $author;
    public Language $language;
}

// ..

$book = new Book([
    'title' => 'Frankenstein',
    'author' => 'Mary Shelley',
    'language' => 'en'
]);

var_dump($book);