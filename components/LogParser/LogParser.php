<?php


namespace app\components\LogParser;


class LogParser
{
    /**
     * @var LineParser
     */
    private $parser;

    public function __construct()
    {
        $this->parser = new NginxLineParser();
    }

    /**
     * @return FileIterator
     */
    public function parseFile($filename)
    {
        return new FileIterator($this->parser, $filename);
    }
}