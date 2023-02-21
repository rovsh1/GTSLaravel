<?php

namespace Module\Reservation\HotelReservation\Domain\ValueObject;

use DateTime;

class ReservationPeriod
{
    public function __construct(
        private ?DateTime $dateFrom,
        private ?DateTime $dateTo,
        private int $nightsNumber = 0,
    ) {}

    public function dateFrom(): ?DateTime
    {
        return $this->dateFrom;
    }

    public function setDateFrom(DateTime $dateTime): void
    {
        $this->dateFrom = $dateTime;
    }

    public function dateTo(): ?DateTime
    {
        return $this->dateTo;
    }

    public function setDateTo(DateTime $dateTime): void
    {
        $this->dateTo = $dateTime;
    }

    public function nightsNumber(): int
    {
        return $this->nightsNumber;
    }

    public function setNightsNumber(int $nightsNumber): void
    {
        $this->nightsNumber = $nightsNumber;
    }
}
