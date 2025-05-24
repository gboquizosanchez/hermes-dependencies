<?php

declare(strict_types=1);

namespace Hermes\Utilities\Composer;

class ComposerJson
{
    public array $normal;

    public array $dev;

    public function __construct(array $data = [])
    {
        $this->normal = $data['require'] ?? [];
        $this->dev = $data['require-dev'] ?? [];
    }
}
