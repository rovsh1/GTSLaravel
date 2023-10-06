<?php

namespace Module\Booking\Domain\ServiceBooking\Support\Concerns;

use DateTimeInterface;

trait HasArrivalDateTrait
{
    public function arrivalDate(): DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(DateTimeInterface $arrivalDate): void
    {
        $this->arrivalDate = $arrivalDate;
    }
}