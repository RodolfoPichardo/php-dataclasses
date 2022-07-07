<?php

namespace Dataclasses\Utils;

use TypeError;

function last_word(string $sentence): string {
    $last_word_pos = strrpos($sentence, ' ') + 1;
    return substr($sentence, $last_word_pos);
}

// TODO: This is hacky
// This is a function to get the declared class of instance variables before they are initialized.
function get_property_class($obj, $property): string {
    try {
        $obj->{$property} = '';
    } catch(TypeError $e) {
        $classname = last_word($e->getMessage());
        if(str_starts_with($classname, '?')) {
            $classname = substr($classname, 1);
        }
        return $classname;
    }

    throw new TypeError("");
}