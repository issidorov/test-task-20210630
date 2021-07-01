<?php


namespace app\components\LogParser;

/**
 * @property-read string $ip
 * @property-read string $time
 * @property-read string $url
 * @property-read UserAgentEntity $agent
 */
class LogLineEntity
{
    private array $_data;

    public function __construct(string $ip, string $time, string $url, string $agent)
    {
        $this->_data = [
            'ip' => $ip,
            'time' => $time,
            'url' => $url,
            'agent' => new UserAgentEntity($agent),
        ];
    }

    public function __get($key)
    {
        return $this->_data[$key];
    }
}