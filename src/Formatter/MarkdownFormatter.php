<?php

declare(strict_types=1);

namespace Hermes\Formatter;

use Hermes\Utilities\PackageType;

class MarkdownFormatter
{
    public static function formatDependency(
        string $name,
        string $version,
        string $type
    ): string {
        $link = PackageType::isNpm($type)
            ? "https://www.npmjs.com/package/"
            : "https://packagist.org/packages/";

        $badge = self::establishBadge($version);

        $title = self::establishTitle($name);

        $markdown = <<<MARKDOWN
[![Latest Stable Version](https://img.shields.io/badge/{$badge})]({$link}{$name})
MARKDOWN;

        return "- {$title} {$markdown}" . PHP_EOL;
    }

    private static function establishBadge(string $version): string
    {
        $badge = "stable-{$version}-blue";

        if ($version === 'dev-master') {
            return 'master-master-red';
        }

        if (str_contains($version, 'beta')) {
            return "beta-{$version}-yellow";
        }

        return $badge;
    }

    private static function establishTitle(string $name): string
    {
        $slashedName = str_replace('/', '-', $name);

        $sluggedName = str_replace('-', ' ', $slashedName);

        $titledName = ucwords($sluggedName);

        $ucFirstName = ucfirst($titledName);

        return str_replace('At ', '@', $ucFirstName);
    }
}
