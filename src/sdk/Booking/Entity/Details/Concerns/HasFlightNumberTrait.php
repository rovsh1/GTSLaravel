<?php

namespace Sdk\Booking\Entity\Details\Concerns;

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
