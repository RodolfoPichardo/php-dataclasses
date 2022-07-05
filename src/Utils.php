<?php

namespace Dataclasses\Utils;

function last_word(string $sentence): string {
    $last_word_pos = strrpos($sentence, ' ') + 1;
    return substr($sentence, $last_word_pos);
}