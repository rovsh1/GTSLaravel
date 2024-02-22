<?php

namespace Module\Hotel\Quotation\Domain\ValueObject;

use DateTimeImmutable;

final class BookingPeriod
{
    private readonly DateTimeImmutable $dateFrom;

    private readonly DateTimeImmutable $dateTo;

    public function __construct(
        DateTimeImmutable $dateFrom,
        DateTimeImmutable $dateTo,
    ) {
        $this->validatePeriod($dateFrom, $dateTo);
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function dateFrom(): DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function dateTo(): DateTimeImmutable
    {
        return $this->dateTo;
    }

    /**
     * Booking dates array
     * @return DateTimeImmutable[]
     */
    public function dates(): array
    {
        $date = $this->dateFrom;
        $toYmd = $this->dateTo->format('Y-m-d');
        $dates = [$date];
        while ($date->format('Y-m-d') < $toYmd) {
            $date = $date->modify('+1 day');
            $dates[] = $date;
        }

        return $dates;
    }

    private function validatePeriod(DateTimeImmutable $dateFrom, DateTimeImmutable $dateTo): void
    {
        if ($dateFrom->format('Y-m-d') >= $dateTo->format('Y-m-d')) {
            throw new \InvalidArgumentException("Booking period failed: date from less or equal date to");
        }
    }
}
