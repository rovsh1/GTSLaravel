<?php

namespace Sdk\Module\Support;

class DateTimeImmutable extends \DateTimeImmutable
{
    public static function createFromTimestamp(int $timestamp): static
    {
        return new static('@' . $timestamp);
    }
}