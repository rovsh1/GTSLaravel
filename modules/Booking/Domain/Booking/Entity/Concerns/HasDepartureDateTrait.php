<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

use DateTimeInterface;

trait HasDepartureDateTrait
{
    public function departureDate(): ?DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?DateTimeInterface $departureDate): void
    {
        $this->departureDate = $departureDate;
    }
}
