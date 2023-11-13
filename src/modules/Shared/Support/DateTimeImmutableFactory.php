<?php

namespace Module\Shared\Support;

use DateTimeImmutable;

abstract class DateTimeImmutableFactory
{
    public static function createFromTimestamp(int $timestamp): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTimestamp($timestamp);
    }
}