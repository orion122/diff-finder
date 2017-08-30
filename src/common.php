<?php

namespace DiffFinder\common;

function genDiff($file1, $file2, $format)
{
    $file1Extension = pathinfo($file1, PATHINFO_EXTENSION);
    $file2Extension = pathinfo($file2, PATHINFO_EXTENSION);

    $fileArray1 = \DiffFinder\parser\dataToArray($file1Extension, file_get_contents($file1));
    $fileArray2 = \DiffFinder\parser\dataToArray($file2Extension, file_get_contents($file2));

    $result = \DiffFinder\diffFinder\findDiff($fileArray1, $fileArray2, $format);

    echo $result;
}
