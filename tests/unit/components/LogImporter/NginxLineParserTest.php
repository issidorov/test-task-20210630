<?php


namespace tests\unit\components\LogImporter;


use app\components\LogParser\NginxLineParser;

class NginxLineParserTest extends \Codeception\Test\Unit
{
    public function test()
    {
        $string = '127.0.0.1 - - [21/Mar/2019:00:20:06 +0300] "GET /favicon/favicon-32.png HTTP/1.1" 200 1306 ' .
            '"http://modimio.loc/icms/catalog/catalog_edit?id=4" "Mozilla/5.0 (X11; Linux x86_64) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.75 Safari/537.36"';
        $parser = new NginxLineParser();
        $model = $parser->parse($string);

        $this->assertEquals('127.0.0.1', $model->ip);
        $this->assertEquals('21/Mar/2019:00:20:06 +0300', $model->time);
        $this->assertEquals('/favicon/favicon-32.png', $model->url);
        $this->assertEquals('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) ' .
            'Chrome/73.0.3683.75 Safari/537.36', $model->agent->source);
        $this->assertEquals('Linux', $model->agent->os);
        $this->assertEquals('Chrome', $model->agent->browser);
    }
}