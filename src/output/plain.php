<?php

namespace DiffFinder\output\plain;

use function \Funct\Collection\flattenAll;

function buildLine($changeType, $property, $value1 = '', $value2 = '')
{
    if ($changeType === 'removed') {
        return "Property '$property' was $changeType";
    } elseif ($changeType === 'added') {
        return "Property '$property' was $changeType with value: '$value1'";
    } elseif ($changeType === 'changed') {
        return "Property '$property' was $changeType. From '$value1' to '$value2'";
    }
}


function genOutput($AST, $parents = '')
{
    $result = array_map(function ($array) use ($parents) {
        if ($array['type'] === 'nested') {
            return genOutput($array['children'], "$parents{$array['key']}.");
        } else {
            if ($array['type'] === 'changed') {
                return buildLine('changed', $parents . $array['key'], $array['from'], $array['to']);
            } elseif ($array['type'] === 'removed') {
                return buildLine('removed', $parents . $array['key']);
            } elseif ($array['type'] === 'added') {
                if (is_array($array['from'])) {
                    return buildLine('added', $parents . $array['key'], 'complex value');
                } else {
                    return buildLine('added', $parents . $array['key'], $array['from']);
                }
            }
        }
    }, $AST);

    return implode(PHP_EOL, array_filter(flattenAll($result)));
}
