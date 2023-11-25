<?php

declare(strict_types=1);

namespace Sdk\Shared\ValueObject;

use Sdk\Module\Support\DateTimeImmutable;

final class Date extends DateTimeImmutable
{
    public function __construct($datetime = "now", \DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
        $this->setTime(0, 0, 0, 0);
    }

    public function value(): int
    {
        return $this->getTimestamp();
    }

    public function __toString()
    {
        return $this->format('d.m.Y');
    }
}
