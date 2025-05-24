<?php

declare(strict_types=1);

namespace Hermes\Utilities;

use RuntimeException;

class JsonParse
{
    public static function encode(
        mixed $value,
        int $options = 0,
        int $depth = 512
    ) : string {
        $result = json_encode($value, $options, $depth);

        if ($result) {
            return $result;
        }

        // See Json error codes
        throw new RuntimeException((string) json_last_error());
    }

    public static function decode(
        mixed $json,
        bool $assoc = false,
        int $depth = 512,
        int $options = 0,
    ) {
        $result = json_decode($json, $assoc, $depth, $options);

        if (! empty($result)) {
            return $result;
        }

        // See Json error codes
        throw new RuntimeException((string) json_last_error());
    }
}
