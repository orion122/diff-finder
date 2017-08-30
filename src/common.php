<?php

namespace DiffFinder;

function genDiff($file1, $file2, $format)
{
    $file1Extension = pathinfo($file1, PATHINFO_EXTENSION);
    $file2Extension = pathinfo($file2, PATHINFO_EXTENSION);

    $fileArray1 = \DiffFinder\dataToArray($file1Extension, file_get_contents($file1));
    $fileArray2 = \DiffFinder\dataToArray($file2Extension, file_get_contents($file2));

    $result = \DiffFinder\findDiff($fileArray1, $fileArray2, $format);

    echo $result;
}
