<?php

namespace DiffFinder\diff;

use function \Funct\Collection\union;

function findDiff($array1, $array2, $format)
{
//    $resultArray = arraysDiff($array1, $array2);
//
//    if ($format === 'plain') {
//        return \DiffFinder\output\outputPlain($resultArray);
//    } elseif ($format === 'json') {
//        return \DiffFinder\output\outputJSON($resultArray);
//    }
//
//    return "{\n".\DiffFinder\output\outputPretty($resultArray)."}";

    $resultArray = buildAST($array1, $array2);
    return "{\n".\DiffFinder\output\outputPrettyAST($resultArray)."}";
}


function arraysDiff($array1, $array2)
{
    $unionArraysKeys = union(array_keys($array1), array_keys($array2));

    return array_reduce($unionArraysKeys, function ($acc, $key) use ($array1, $array2) {
        if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
            if (is_array($array1[$key]) && is_array($array2[$key])) {
                if ($array1[$key] === $array2[$key]) {
                    $acc[] = buildArray($key, true, 'unchanged', arraysDiff($array1[$key], $array1[$key]), null);
                } else {
                    $acc[] = buildArray($key, true, 'changed', arraysDiff($array1[$key], $array2[$key]), null);
                }
            } else {
                if ($array1[$key] === $array2[$key]) {
                    $acc[] = buildArray($key, false, 'unchanged', $array1[$key], null);
                } else {
                    $acc[] = buildArray($key, false, 'changed', $array1[$key], $array2[$key]);
                }
            }
        } elseif (array_key_exists($key, $array1)) {
            if (is_array($array1[$key])) {
                $acc[] = buildArray($key, true, 'removed', arraysDiff($array1[$key], $array1[$key]), null);
            } else {
                $acc[] = buildArray($key, false, 'removed', $array1[$key], null);
            }
        } elseif (is_array($array2[$key])) {
            $acc[] = buildArray($key, true, 'added', arraysDiff($array2[$key], $array2[$key]), null);
        } else {
            $acc[] = buildArray($key, false, 'added', $array2[$key], null);
        }
        return $acc;
    }, []);
}


function buildArray($key, $isNested, $changeType, $from, $to)
{
    return [
        'key'        => $key,
        'isNested'   => $isNested,
        'changeType' => $changeType,
        'from'       => $from,
        'to'         => $to
    ];
}

//======================================================================================================================

function buildAST($array1, $array2)
{
    $unionArraysKeys = union(array_keys($array1), array_keys($array2));

    return array_reduce($unionArraysKeys, function ($acc, $key) use ($array1, $array2) {
        if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
            if (is_array($array1[$key]) && is_array($array2[$key])) {
                if ($array1[$key] === $array2[$key]) {
                    $acc[] = buildArrayForAST($key, 'nested', 'unchanged', $array1[$key]);
                } else {
                    $acc[] = buildArrayForAST($key, 'nested', 'changed', buildAST($array1[$key], $array2[$key]));
                }
            } else {
                if ($array1[$key] === $array2[$key]) {
                    $acc[] = buildArrayForAST($key, 'plain', 'unchanged', null, $array1[$key]);
                } else {
                    $acc[] = buildArrayForAST($key, 'plain', 'changed', null, $array1[$key], $array2[$key]);
                }
            }
        } elseif (array_key_exists($key, $array1)) {
            if (is_array($array1[$key])) {
                $acc[] = buildArrayForAST($key, 'nested', 'removed', $array1[$key]);
            } else {
                $acc[] = buildArrayForAST($key, 'plain', 'removed', null, $array1[$key]);
            }
        } elseif (is_array($array2[$key])) {
            $acc[] = buildArrayForAST($key, 'nested', 'added', $array2[$key]);
        } else {
            $acc[] = buildArrayForAST($key, 'plain', 'added', null, null, $array2[$key]);
        }
        return $acc;
    }, []);
}


function buildArrayForAST($key, $nodeType, $changeType, $children, $from = null, $to = null)
{
    if ($nodeType === 'nested') {
        return [
            'key'        => $key,
            'nodeType'   => $nodeType,
            'changeType' => $changeType,
            'children'   => $children
        ];
    }

    return [
        'key'        => $key,
        'nodeType'   => $nodeType,
        'changeType' => $changeType,
        'from'       => $from,
        'to'         => $to
    ];
}
