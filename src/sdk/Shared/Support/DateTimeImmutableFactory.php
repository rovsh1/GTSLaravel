<?php

namespace Sdk\Shared\Support;

use Sdk\Module\Support\DateTimeImmutable;

abstract class DateTimeImmutableFactory
{
    public static function createFromTimestamp(int $timestamp): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTimestamp($timestamp);
    }
}
