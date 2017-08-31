<?php
namespace DiffFinder\Tests;

use \PHPUnit\Framework\TestCase;

class DiffFinderTest extends TestCase
{
    public function testPretty()
    {
        $filePath1 = 'tests/fixtures/before.json';
        $filePath2 = 'tests/fixtures/after.json';

        $diffResult = file_get_contents('tests/fixtures/diff-result-pretty');

        $this->assertEquals("$diffResult", \DiffFinder\common\genDiff($filePath1, $filePath2, 'pretty'));
    }

    public function testPrettyNested()
    {
        $filePath1 = 'tests/fixtures/before-nested.json';
        $filePath2 = 'tests/fixtures/after-nested.json';

        $diffResultNested = file_get_contents('tests/fixtures/diff-result-pretty-nested');

        $this->assertEquals("$diffResultNested", \DiffFinder\common\genDiff($filePath1, $filePath2, 'pretty'));
    }


    public function testPlainNested()
    {
        $filePath1 = 'tests/fixtures/before-nested.json';
        $filePath2 = 'tests/fixtures/after-nested.json';

        $diffResult = file_get_contents('tests/fixtures/diff-result-plain-nested');

        $this->assertEquals("$diffResult", \DiffFinder\common\genDiff($filePath1, $filePath2, 'plain'));
    }
}
