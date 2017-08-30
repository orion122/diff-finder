<?php

namespace DiffFinder\parser;

use Symfony\Component\Yaml\Yaml;

function dataToArray($dataFormat, $data)
{
    if ($dataFormat === 'json') {
        return json_decode($data, true);
    } elseif ($dataFormat === 'yml') {
        return Yaml::parse($data);
    }
}
