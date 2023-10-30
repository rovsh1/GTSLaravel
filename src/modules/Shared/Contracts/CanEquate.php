<?php

declare(strict_types=1);

namespace Module\Shared\Contracts;

interface CanEquate
{
    public function isEqual(mixed $b): bool;
}
