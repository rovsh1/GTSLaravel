<?php

declare(strict_types=1);

namespace Sdk\Shared\Contracts\Support;

interface CanCompare
{
    public function compareTo(mixed $b): int;
}
