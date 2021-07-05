<?php


namespace app\components\LogParser;


class NginxLineParser implements LineParser
{
    public function parse(string $string): ?LogLineEntity
    {
        $parts = str_getcsv($string, ' ');

        if (count($parts) < 10) {
            return null;
        }

        $ip = $parts[0];
        $time = trim($parts[3] . ' ' . $parts[4], '[]');
        $url = explode(' ', $parts[5])[1] ?? $parts[5];
        $agent = $parts[9];

        return new LogLineEntity($ip, $time, $url, $agent);
    }
}