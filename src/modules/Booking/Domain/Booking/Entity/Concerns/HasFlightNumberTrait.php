<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

trait HasFlightNumberTrait
{
    public function flightNumber(): ?string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(?string $flightNumber): void
    {
        $this->flightNumber = $flightNumber;
    }
}
