<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Support;

interface CanEquate
{
    public function isEqual(mixed $b): bool;
}
