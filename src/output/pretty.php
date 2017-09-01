<?php

namespace DiffFinder\output\pretty;

use function DiffFinder\output\utilities\boolToText;
use function DiffFinder\output\utilities\unpackArray;

function output($AST, $depth = 0)
{
    $result = array_reduce($AST, function ($acc, $array) use ($depth) {
        if ($array['type'] === 'nested') {
            $value = output($array['children'], $depth + 1);
            $acc .= build(true, " ", $array['key'], $value, $depth);
        } else {
            if ($array['type'] === 'unchanged') {
                $acc .= build(false, " ", $array['key'], $array['from'], $depth);
            } elseif ($array['type'] === 'changed') {
                $acc .= build(false, "-", $array['key'], $array['from'], $depth);
                $acc .= build(false, "+", $array['key'], $array['to'], $depth);
            } elseif ($array['type'] === 'removed') {
                $acc .= build(false, "-", $array['key'], $array['from'], $depth);
            } elseif ($array['type'] === 'added') {
                $acc .= build(false, "+", $array['key'], $array['from'], $depth);
            }
        }

        return $acc;
    }, '');

    return $result;
}


function build($isNested, $mark, $key, $value, $depth)
{
    $spaces = str_repeat(' ', $depth * 4 + 2);

    $half1 ="$spaces$mark \"$key\": ";

    if ($isNested) {
        $half2 = "{\n$value$spaces  }\n";
    } else {
        if (!is_array($value)) {
            $half2 = boolToText($value);
        } else {
            $values = unpackArray($value);
            $half2 = "{\n$spaces      $values  $spaces}\n";
        }
    }
    return $half1 . $half2;
}
