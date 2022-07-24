<?php

namespace Dataclasses;

require_once("DictOf.php");

use Attribute;

#[Attribute] class ArrayOf extends DictOf
{
    public function populate($arr)
    {
        parent::populate(array_values($arr));
    }
}