<?php

declare(strict_types=1);

namespace Sdk\Shared\Contracts\Support;

interface CanEquate
{
    public function isEqual(mixed $b): bool;
}
