<?php

declare(strict_types=1);

namespace Module\Shared\ValueObject;

final class File
{
    public function __construct(
        private readonly string $guid,
    ) {
    }

    public function guid(): string
    {
        return $this->guid;
    }
}
