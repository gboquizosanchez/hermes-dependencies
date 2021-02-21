<?php

declare(strict_types=1);

namespace Hermes\Parser;

class Writer
{
    /**
     * @var false|resource
     */
    protected $readme;

    public function __construct(string $path, string $output)
    {
        $this->readme = fopen("{$path}/{$output}.md", 'wb+');
    }

    protected function write(string $string): void
    {
        fwrite($this->readme, $string);
    }

    protected function writeln(): void
    {
        fwrite($this->readme, PHP_EOL);
    }
}
