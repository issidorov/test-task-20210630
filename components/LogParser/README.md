# LogParser

Example usage:
```php
use app\components\LogParser\LogParser;

$parser = new LogParser();
$logs = $parser->parseFile('access.log');
foreach ($logs as $line) {
    echo implode(', ', [$line->ip, $line->time, $line->agent->browser]);
}
```
