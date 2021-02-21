<?php

declare(strict_types=1);

namespace Hermes\Formatter;

class MarkdownFormatter
{
    public static function formatDependency(string $name, string $version, string $type): string
    {
        $link = $type === 'npm' ? "https://www.npmjs.com/package/" : "https://packagist.org/packages/";

        $badge = self::establishBadge($version);

        $title = self::establishTitle($name);

        $markdown = "[![Latest Stable Version](https://img.shields.io/badge/{$badge})]({$link}{$name})";

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
        return str_replace('At ', '@', (ucfirst(title_case(str_slug(str_replace('/', '-', $name), ' ')))));
    }
}
