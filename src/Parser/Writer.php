<?php

declare(strict_types=1);

namespace Hermes\Parser;

use Hermes\Formatter\MarkdownFormatter;
use Hermes\Utilities\PackageType;

class Writer
{
    /**
     * @var false|resource
     */
    protected $readme;

    protected string $path;

    protected string $output;

    public function __construct(string $path, string $output)
    {
        $this->path = $path;
        $this->output = $output;
    }

    public function init(): void
    {
        $this->readme = fopen("{$this->path}/{$this->output}.md", 'wb+');
    }

    public function setOutput(string $output): void
    {
        $this->output = $output;
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
            $version = PackageType::isNpm($type)
                ? str_replace('^', '', $version)
                : $version;

            $this->write(
                MarkdownFormatter::formatDependency(
                    $name,
                    $version,
                    $type
                )
            );
        }

        if ($hasDependencies) {
            $this->writeln();
        }
    }
}
