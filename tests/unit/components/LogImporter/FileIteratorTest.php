<?php


namespace tests\unit\components\LogImporter;


use app\components\LogParser\FileIterator;
use app\components\LogParser\NginxLineParser;

class FileIteratorTest extends \Codeception\Test\Unit
{
    public function testForeach()
    {
        $expected = require (__DIR__ . '/1_expected.php');

        $lineParser = new NginxLineParser();
        $iterator = new FileIterator($lineParser, __DIR__ . '/1_sources.log');

        $actual = [];
        foreach ($iterator as $k => $item) {
            $actual[$k] = [$item->ip, $item->time, $item->url, $item->agent->os, $item->agent->browser];
        }

        $this->assertEquals($expected, $actual);
    }

    public function testRewind()
    {
        $expected = require (__DIR__ . '/1_expected.php');

        $lineParser = new NginxLineParser();
        $iterator = new FileIterator($lineParser, __DIR__ . '/1_sources.log');

        // First iterable
        foreach ($iterator as $item) {
            $actual[] = [$item->ip, $item->time, $item->url, $item->agent->os, $item->agent->browser];
        }

        $actual = [];
        // Second iterable with reset (used rewind)
        foreach ($iterator as $item) {
            $actual[] = [$item->ip, $item->time, $item->url, $item->agent->os, $item->agent->browser];
        }

        $this->assertEquals($expected, $actual);

    }
}