<?php

namespace Sdk\Module\Support;

use DateTimeInterface;

class DateTimeImmutable extends \DateTimeImmutable
{
    public static function createFromTimestamp(int $timestamp): static
    {
        return new static('@' . $timestamp);
    }

    public static function createFromInterface(DateTimeInterface $object): static
    {
        return self::createFromTimestamp($object->getTimestamp());
    }
}