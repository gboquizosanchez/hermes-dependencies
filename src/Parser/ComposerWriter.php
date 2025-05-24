<?php

declare(strict_types=1);

namespace Hermes\Parser;

use Hermes\Utilities\PackageType;
use MCStreetguy\ComposerParser\ComposerJson;
use MCStreetguy\ComposerParser\Factory as ComposerParser;
use MCStreetguy\ComposerParser\Lockfile;
use MCStreetguy\ComposerParser\Service\PackageMap;

class ComposerWriter extends Writer
{
    private ComposerJson $jsonFile;

    private Lockfile $lockFile;

    public function init(): void
    {
        parent::init();

        $this->lockFile = ComposerParser::parse("{$this->path}/composer.lock");
        $this->jsonFile = ComposerParser::parse("{$this->path}/composer.json");
    }

    public function parse(): void
    {
        $values = [
            'normal' => '### PHP dependencies 📦',
            'dev' => '#### Develop dependencies 🔧',
        ];

        foreach ($values as $key => $value) {
            $type = $key === 'normal' ? '' : $key;

            $this->writeDependencies(
                $this->dependencies($type),
                $value,
                PackageType::Composer->value,
            );
        }
    }

    private function dependencies(string $type = ''): array
    {
        $locker = $this->lockFileVersions($type);

        $requires = $this->jsonFile->{"getRequire{$type}"}()->getData();

        return array_intersect_key($locker, $requires);
    }

    private function lockFileVersions(string $type): array
    {
        /** @var PackageMap $array */
        $array = $this->lockFile->{"getPackages{$type}"}();

        $mapping = static fn (array $item): array => [
            $item['name'] => $item['version'],
        ];

        return array_collapse(array_map($mapping, $array->getData()));
    }
}
