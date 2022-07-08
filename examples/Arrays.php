<?php

require_once("../src/Dataclass.php");

use Dataclasses\Dataclass;

class Article extends Dataclass
{
    public string $title;
    public string $text;
    public array $tags;
}

$article = new Article([
    'title' => 'Lorem Ipsum',
    'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi varius faucibus quam at malesuada. In hac'.
                'habitasse platea dictumst. Nulla facilisi. Duis lectus orci, maximus sed leo et, eleifend vehicula '.
                'ligula. Quisque suscipit enim sit amet orci feugiat, in porta quam dictum. Ut sit amet justo ut nisl '.
                'volutpat euismod. Aliquam eu congue felis, tincidunt rutrum ex. Suspendisse potenti. Sed pulvinar ' .
                'orci lacus, non pellentesque augue rhoncus id. Duis non velit odio.',
    'tags' => ['maximus', 'consectetur']
]);

var_dump($article);