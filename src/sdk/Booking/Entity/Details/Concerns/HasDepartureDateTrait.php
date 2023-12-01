<?php

namespace Sdk\Booking\Entity\Details\Concerns;

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

    public function serviceDate(): ?DateTimeInterface
    {
        return $this->departureDate;
    }
}
