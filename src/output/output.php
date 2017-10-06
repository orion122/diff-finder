<?php

namespace DiffFinder\output;

function output($AST, $format)
{
    if ($format === 'plain') {
        return \DiffFinder\output\plain\genOutput($AST);
    } elseif ($format === 'json') {
        return \DiffFinder\output\json\genOutput($AST);
    }

    return "{\n".\DiffFinder\output\pretty\genOutput($AST)."}\n";
}
