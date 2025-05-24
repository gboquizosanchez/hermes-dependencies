<?php

declare(strict_types=1);

namespace Hermes\Parser;

use Hermes\Utilities\Composer\ComposerJson;
use Hermes\Utilities\Composer\LockFile;
use Hermes\Utilities\ComposerParser;
use Hermes\Utilities\PackageType;

class ComposerWriter extends Writer
{
    private ComposerJson $jsonFile;

    private LockFile $lockFile;

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
            $this->writeDependencies(
                $this->dependencies($key),
                $value,
                PackageType::Composer->value,
            );
        }
    }

    private function dependencies(string $type = ''): array
    {
        $locker = $this->lockFileVersions($type);

        $requires = $this->jsonFile->{$type};

        return array_intersect_key($locker, $requires ?? []);
    }

    private function lockFileVersions(string $type): array
    {
        $array = $this->lockFile->{$type};

        $mapping = static fn (array $item): array => [
            $item['name'] => $item['version'],
        ];

        return array_merge(...array_map($mapping, $array ?? []));
    }
}
