<?php

declare(strict_types=1);

namespace Hermes\Utilities\Composer;

class LockFile
{
    public array $normal;

    public array $dev;

    public function __construct(array $data = [])
    {
        $this->normal = $data['packages'] ?? [];
        $this->dev = $data['packages-dev'] ?? [];
    }
}
