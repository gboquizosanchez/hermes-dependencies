<?php

declare(strict_types=1);

namespace Hermes\Utilities;

class PackageType
{
    public const NPM = 'npm';
    public const COMPOSER = 'composer';

    public static function isNpm(string $string): bool
    {
        return $string === self::NPM;
    }

    public static function isComposer(string $string): bool
    {
        return $string === self::COMPOSER;
    }

    public static function values(): array
    {
        return [self::NPM, self::COMPOSER];
    }
}
