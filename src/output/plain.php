<?php

namespace DiffFinder\output\plain;

function genOutput($AST, $parents = '')
{
    $result = array_reduce($AST, function ($acc, $array) use ($parents) {
        if ($array['type'] === 'nested') {
            $acc .= genOutput($array['children'], "$parents{$array['key']}.");
        } else {
            if ($array['type'] === 'changed') {
                $acc .= buildLine('changed', $parents . $array['key'], $array['from'], $array['to']);
            } elseif ($array['type'] === 'removed') {
                $acc .= buildLine('removed', $parents . $array['key']);
            } elseif ($array['type'] === 'added') {
                if (is_array($array['from'])) {
                    $acc .= buildLine('added', $parents . $array['key'], 'complex value');
                } else {
                    $acc .= buildLine('added', $parents . $array['key'], $array['from']);
                }
            }
        }

        return $acc;
    }, '');

    return $result;
}


function buildLine($changeType, $property, $value1 = '', $value2 = '')
{
    $line = '';

    if ($changeType === 'removed') {
        $line = "'$property' was $changeType";
    } elseif ($changeType === 'added') {
        $line = "'$property' was $changeType with value: '$value1'";
    } elseif ($changeType === 'changed') {
        $line = "'$property' was $changeType. From '$value1' to '$value2'";
    }

    return "Property $line\n";
}
