<?php

namespace Module\Booking\Domain\ServiceBooking\Support\Concerns;

trait HasFlightNumberTrait
{
    public function flightNumber(): string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(string $flightNumber): void
    {
        $this->flightNumber = $flightNumber;
    }
}