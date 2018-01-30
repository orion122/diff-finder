# Diff Finder

[![Build Status](https://travis-ci.org/orion122/diff-finder.svg?branch=master)](https://travis-ci.org/orion122/diff-finder)
[![Maintainability](https://api.codeclimate.com/v1/badges/3b7d5bea3135e3996607/maintainability)](https://codeclimate.com/github/orion122/diff-finder/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/3b7d5bea3135e3996607/test_coverage)](https://codeclimate.com/github/orion122/diff-finder/test_coverage)

## Description
Utility for finding differences in configuration files.

Supported JSON and YAML files.

## Installation
`$ composer require eq/diff`

## Usage
```
$ ~/vendor/bin/gendiff -h
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]
```
Pretty output:
```
$ ~/vendor/bin/gendiff first.json second.json
   {
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
   }
```
Plain output:
```
$ ~/vendor/bin/gendiff --format plain first.json second.json
Property 'common.setting2' was removed
Property 'common.setting6' was removed
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: 'complex value'
Property 'group1.baz' was changed. From 'bas' to 'bars'
Property 'group2' was removed
Property 'group3' was added with value: 'complex value'
```
JSON output:
```
$ ~/vendor/bin/gendiff --format json first.json second.json
[{"key":"common","type":"nested","children":[{"key":"setting1","type":"unchanged","from":"Value 1","to":null},{"key":"setting2","type":"removed","from":"200","to":null},{"key":"setting3","type":"unchanged","from":true,"to":null},{"key":"setting6","type":"removed","from":{"key":"value"},"to":null},{"key":"setting4","type":"added","from":"blah blah","to":null},{"key":"setting5","type":"added","from":{"key5":"value5"},"to":null}]},{"key":"group1","type":"nested","children":[{"key":"baz","type":"changed","from":"bas","to":"bars"},{"key":"foo","type":"unchanged","from":"bar","to":null}]},{"key":"group2","type":"removed","from":{"abc":"12345"},"to":null},{"key":"group3","type":"added","from":{"fee":"100500"},"to":null}]
```
