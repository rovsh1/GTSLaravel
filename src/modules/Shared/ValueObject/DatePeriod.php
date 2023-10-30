<?php

declare(strict_types=1);

namespace Module\Shared\ValueObject;

use Exception;
use Sdk\Module\Support\DateTimeImmutable;

final class DatePeriod
{
    public function __construct(
        private readonly DateTimeImmutable $start,
        private readonly DateTimeImmutable $end,
    ) {
        $this->start->setTime(0, 0, 0, 0);
        $this->end->setTime(0, 0, 0, 0);
    }

    public function start(): DateTimeImmutable
    {
        return $this->start;
    }

    public function end(): DateTimeImmutable
    {
        return $this->end;
    }

    /**
     * @return array<int, DateTimeImmutable>
     * @throws Exception
     */
    public function includedDates(): array
    {
        $end = $this->end->format('Ymd');
        $dates = [];
        $date = $this->start;
        do {
            $dates[] = $date;
            $date = $date->modify('+1 day');
        } while ($date->format('Ymd') < $end);

        return $dates;
    }
}