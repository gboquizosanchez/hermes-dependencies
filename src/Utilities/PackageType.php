<?php

declare(strict_types=1);

namespace Hermes\Utilities;

enum PackageType: string
{
    case Npm = 'npm';
    case Composer = 'composer';

    public static function isNpm(string $string): bool
    {
        return $string === self::Npm->value;
    }

    public static function isComposer(string $string): bool
    {
        return $string === self::Composer->value;
    }

    public static function values(): array
    {
        return [
            self::Npm->value,
            self::Composer->value,
        ];
    }
}
