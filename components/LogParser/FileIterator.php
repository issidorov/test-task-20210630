<?php


namespace app\components\LogParser;


class FileIterator implements \Iterator
{
    private LineParser $lineParser;
    private string $filename;
    /** @var resource */
    private $fd;
    private ?LogLineEntity $line;
    private int $position = 0;

    public function __construct(LineParser $lineParser, string $filename)
    {
        $this->lineParser = $lineParser;
        $this->filename = $filename;
        $this->rewind();
    }

    public function current()
    {
        if ($this->line === null) {
            if (feof($this->fd)) {
                return null;
            }
            $string = fgets($this->fd);
            $this->line = $this->lineParser->parse($string);
        }
        return $this->line;
    }

    public function next()
    {
        $this->position++;
        $this->line = null;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->current() !== null;
    }

    public function rewind()
    {
        $this->fd = fopen($this->filename, 'r');
        $this->line = null;
        $this->position = 0;
    }
}