<?php


namespace app\components\LogParser;

use Jenssegers\Agent\Agent;

/**
 * @property-read string $source
 * @property-read string $os
 * @property-read string $browser
 */
class UserAgentEntity
{
    private array $_data;

    public function __construct($source)
    {
        $detector = new Agent();
        $detector->setUserAgent($source);
        $this->_data = [
            'source' => $source,
            'os' => $detector->platform(),
            'browser' => $detector->browser(),
        ];
    }

    public function __get($key)
    {
        return $this->_data[$key];
    }
}