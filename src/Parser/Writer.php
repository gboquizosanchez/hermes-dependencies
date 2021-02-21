<?php

declare(strict_types=1);

namespace Hermes\Parser;

use Hermes\Formatter\MarkdownFormatter;

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

    protected function writeDependencies(array $dependencies, string $value, string $type): void
    {
        $hasDependencies = count($dependencies) > 1;

        if ($hasDependencies) {
            $this->write($value);
            $this->writeln();
        }

        foreach ($dependencies as $name => $version) {
            $version = $type === 'npm' ? str_replace('^', '', $version) : $version;

            $this->write(MarkdownFormatter::formatDependency($name, $version, $type));
        }

        if ($hasDependencies) {
            $this->writeln();
        }
    }
}
