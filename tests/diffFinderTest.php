<?php
namespace Diff\Tests;

class DiffFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testFindDiff()
    {
        $diffResult = '{
    "host": "hexlet.io"
  - "timeout": "50"
  + "timeout": "20"
  - "proxy": "123.234.53.22"
  + "verbose": true
}';
        $array1 = ['host' => 'hexlet.io', 'timeout' => 50, 'proxy' => '123.234.53.22'];
        $array2 = ['timeout' => 20, 'verbose' => true, 'host' => 'hexlet.io'];

        $this->assertEquals("$diffResult", \DiffFinder\diff\findDiff($array1, $array2, 'pretty'));
    }

    public function testFindDiffNestedPretty()
    {
        $array1 = ["common" => [
            "setting1" => "Value 1",
            "setting2" => "200",
            "setting3" => true,
            "setting6" => [
                "key" => "value"
            ]
        ],
            "group1" => [
                "baz" => "bas",
                "foo" => "bar"
            ],
            "group2" => [
                "abc" => "12345"
            ]];

        $array2 = ["common" => [
            "setting1" => "Value 1",
            "setting3" => true,
            "setting4" => "blah blah",
            "setting5" => [
                "key5" => "value5"
            ]
        ],
            "group1" => [
                "foo" => "bar",
                "baz" => "bars"
            ],
            "group3" => [
                "fee" => "100500"
            ]];

        $diffResult = '{
    "common": {
        "setting1": "Value 1"
      - "setting2": "200"
        "setting3": true
      - "setting6": {
            "key": "value"
        }
      + "setting4": "blah blah"
      + "setting5": {
            "key5": "value5"
        }
    }
    "group1": {
      - "baz": "bas"
      + "baz": "bars"
        "foo": "bar"
    }
  - "group2": {
        "abc": "12345"
    }
  + "group3": {
        "fee": "100500"
    }
}';

        $this->assertEquals("$diffResult", \DiffFinder\diff\findDiff($array1, $array2, 'pretty'));
    }


    public function testFindDiffNestedPlain()
    {
        $array1 = ["common" => [
            "setting1" => "Value 1",
            "setting2" => "200",
            "setting3" => true,
            "setting6" => [
                "key" => "value"
            ]
        ],
            "group1" => [
                "baz" => "bas",
                "foo" => "bar"
            ],
            "group2" => [
                "abc" => "12345"
            ]];

        $array2 = ["common" => [
            "setting1" => "Value 1",
            "setting3" => true,
            "setting4" => "blah blah",
            "setting5" => [
                "key5" => "value5"
            ]
        ],
            "group1" => [
                "foo" => "bar",
                "baz" => "bars"
            ],
            "group3" => [
                "fee" => "100500"
            ]];

        $diffResult = "Property 'common.setting2' was removed
Property 'common.setting6' was removed
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: 'complex value'
Property 'group1.baz' was changed. From 'bas' to 'bars'
Property 'group2' was removed
Property 'group3' was added with value: 'complex value'\n";

        $this->assertEquals("$diffResult", \DiffFinder\diff\findDiff($array1, $array2, 'plain'));
    }
}
