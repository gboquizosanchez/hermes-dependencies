<?php

declare(strict_types=1);

namespace Hermes\Utilities;

class ArgsChecker
{
    public static function isComposer(array $arguments): bool
    {
        return self::checker($arguments, 'c', 'composer');
    }

    public static function isNpm(array $arguments): bool
    {
        return self::checker($arguments, 'p', 'package');
    }

    public static function isAll(array $arguments): bool
    {
        return self::checker($arguments, 'a', 'all');
    }

    private static function checker(
        array $arguments,
        string $short,
        string $long
    ): bool {
        return isset($arguments[$long]) || isset($arguments[$short]);
    }
}
