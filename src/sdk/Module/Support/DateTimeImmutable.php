<?php

namespace Sdk\Module\Support;

use DateTimeInterface;

class DateTimeImmutable extends \DateTimeImmutable
{
    public static function createFromTimestamp(int $timestamp): static
    {
        $timezone = new \DateTimeZone(date_default_timezone_get());

        return (new static('@' . $timestamp))->setTimezone($timezone);
    }

    public static function createFromInterface(DateTimeInterface $object): static
    {
        return self::createFromTimestamp($object->getTimestamp());
    }
}
