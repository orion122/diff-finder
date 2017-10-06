<?php

namespace DiffFinder\output\utilities;

function boolToText($value)
{
    if ($value === true) {
        return "true\n";
    } elseif ($value === false) {
        return "false\n";
    }

    return "\"$value\"\n";
}


function unpackArray($array, $spaces)
{
    return array_reduce(array_keys($array), function ($acc, $key) use ($array, $spaces) {
        $value = boolToText($array[$key]);
        $acc .= "$spaces\"$key\": $value";
        return $acc;
    }, '');
}
