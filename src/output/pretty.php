<?php

namespace DiffFinder\output\pretty;

use function \Funct\Collection\flattenAll;
use function DiffFinder\output\utilities\boolToText;
use function DiffFinder\output\utilities\unpackArray;

function genOutput($AST, $depth = 0)
{
    $result = array_map(function ($array) use ($depth) {
        if ($array['type'] === 'nested') {
            $value = genOutput($array['children'], $depth + 1);
            return buildLine(true, " ", $array['key'], $value, $depth);
        } else {
            if ($array['type'] === 'unchanged') {
                return buildLine(false, " ", $array['key'], $array['from'], $depth);
            } elseif ($array['type'] === 'changed') {
                $value1 = buildLine(false, "-", $array['key'], $array['from'], $depth);
                $value2 = buildLine(false, "+", $array['key'], $array['to'], $depth);
                return [$value1, $value2];
            } elseif ($array['type'] === 'removed') {
                return buildLine(false, "-", $array['key'], $array['from'], $depth);
            } elseif ($array['type'] === 'added') {
                return buildLine(false, "+", $array['key'], $array['from'], $depth);
            }
        }
    }, $AST);

    return implode('', flattenAll($result));
}


function buildLine($isNested, $mark, $key, $value, $depth)
{
    $spaces = str_repeat(' ', $depth * 4 + 2);

    $half1 ="$spaces$mark \"$key\": ";

    if ($isNested) {
        $half2 = "{\n$value$spaces  }\n";
    } else {
        if (!is_array($value)) {
            $half2 = boolToText($value);
        } else {
            $values = unpackArray($value, "$spaces      ");
            $half2 = "{\n$values  $spaces}\n";
        }
    }
    return $half1 . $half2;
}
