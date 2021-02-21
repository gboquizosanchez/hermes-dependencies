<?php

declare(strict_types=1);

namespace Hermes\Parser;

use Hermes\Formatter\MarkdownFormatter;
use MCStreetguy\ComposerParser\ComposerJson;
use MCStreetguy\ComposerParser\Factory as ComposerParser;
use MCStreetguy\ComposerParser\Lockfile;
use Tightenco\Collect\Support\Collection;

class ComposerWriter extends Writer
{
    /**
     * @var ComposerJson
     */
    private $jsonFile;

    /**
     * @var Lockfile
     */
    private $lockFile;

    public function __construct(string $path, string $output)
    {
        parent::__construct($path, $output);

        $this->lockFile = ComposerParser::parse("{$path}/composer.lock");
        $this->jsonFile = ComposerParser::parse("{$path}/composer.json");
    }

    public function parse(): void
    {
        $values = ['normal' => '### PHP dependencies 📦', 'dev' => '#### Develop dependencies 🔧'];

        foreach ($values as $key => $value) {
            $type = $key === 'normal' ? '' : $key;

            $dependencies = $this->dependencies($type);

            if ($dependencies->isNotEmpty()) {
                $this->write($value);
                $this->writeln();
            }

            $dependencies->each(fn (string $version, string $name) => $this->write(
                MarkdownFormatter::formatDependency($name, $version, 'composer')
            ));

            if ($dependencies->isNotEmpty()) {
                $this->writeln();
            }
        }
    }

    private function dependencies(string $type = ''): Collection
    {
        $locker = $this->lockFileVersions($type);

        $requires = $this->jsonFile->{"getRequire{$type}"}()->getData();

        return $locker->intersectByKeys($requires);
    }

    private function lockFileVersions(string $type): Collection
    {
        $collection = new Collection($this->lockFile->{"getPackages{$type}"}());

        return $collection->map(fn (array $item): array => [
            $item['version']['name'] => $item['version']['version'],
        ])->collapse();
    }
}
