<?php

declare(strict_types=1);

namespace Hermes\Utilities;

use Error;
use Hermes\Utilities\Composer\ComposerJson;
use Hermes\Utilities\Composer\LockFile;
use InvalidArgumentException;
use RuntimeException;

class ComposerParser
{
    public static function parse(
        string $path,
        bool $ignoreGlobal = false,
    ) {
        if (preg_match('/composer\.json$/', $path)) {
            return self::parseComposerJson($path);
        }

        if (preg_match('/composer\.lock$/', $path)) {
            return self::parseLockfile($path);
        }

        throw new InvalidArgumentException(
            "File at path '$path' is neither a composer.lock or a composer.json file!",
            1527613100,
        );
    }

    public static function parseComposerJson(
        string $path,
        bool $ignoreGlobal = false,
    ): ComposerJson {
        $content = self::readJsonFile($path);

        if ($ignoreGlobal === false) {
            $globalConfig = self::resolveGlobalConfiguration();
            $content = \array_merge_recursive($globalConfig, $content);
        }

        return new ComposerJson($content);
    }

    public static function parseLockfile(
        string $path,
        bool $ignoreGlobal = false,
    ): Lockfile {
        $content = self::readJsonFile($path);

        if ($ignoreGlobal === false) {
            $globalConfig = self::resolveGlobalConfiguration();
            $content = \array_merge_recursive($globalConfig, $content);
        }

        return new Lockfile($content);
    }

    protected static function readJsonFile(string $path): array
    {
        if (!\is_file($path) || !\is_readable($path)) {
            throw new InvalidArgumentException("File at path '$path' does not exist or is not readable!", 1527613092);
        }

        try {
            $content = file_get_contents($path);
            $object = json_decode($content, true);
        } catch (Error) {
            throw new RuntimeException("Invalid JSON string! Could not parse file '$path'.", 1527763163);
        } finally {
            return $object;
        }
    }

    protected static function resolveGlobalConfiguration(): array
    {
        try {
            $path = self::resolveGlobalConfigurationPath();
            $config = self::readJsonFile($path);
        } catch (\Exception) {
            return [];
        }

        return $config;
    }

    protected static function resolveGlobalConfigurationPath(): string
    {
        $globalConfigurationPath = \getenv('COMPOSER_HOME');

        if ($globalConfigurationPath !== false) {
            return $globalConfigurationPath . \DIRECTORY_SEPARATOR . 'config.json';
        }

        if (\PHP_OS_FAMILY === 'Windows') {
            $currentUsername = \getenv('USERNAME');

            if ($currentUsername === false) {
                throw new \RuntimeException('Could not determine current username!', 1568044616707);
            }

            return "C:\\Users\\$currentUsername\\AppData\\Roaming\\Composer\\config.json";
        }

        $currentUsername = \posix_getpwuid(\posix_geteuid())['name'];

        if (\PHP_OS_FAMILY === 'Darwin') {
            return "/Users/$currentUsername/.composer/config.json";
        }

        $xdgConfigHome = \getenv('XDG_CONFIG_HOME');
        if ($xdgConfigHome !== false) {
            return "$xdgConfigHome/composer/config.json";
        }

        return "/home/$currentUsername/.composer/config.json";
    }
}
