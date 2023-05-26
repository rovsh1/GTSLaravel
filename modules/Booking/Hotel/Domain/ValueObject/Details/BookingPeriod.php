<?php

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

use DateTime;

final class BookingPeriod
{
    public function __construct(
        private ?DateTime $dateFrom,
        private ?DateTime $dateTo,
        private int $nightsCount = 0,
    ) {}

    public function dateFrom(): ?DateTime
    {
        return $this->dateFrom;
    }

    public function dateTo(): ?DateTime
    {
        return $this->dateTo;
    }

    public function nightsCount(): int
    {
        return $this->nightsCount;
    }
}
