<?php


namespace app\components\LogParser;


interface LineParser
{
    public function parse(string $string): ?LogLineEntity;
}