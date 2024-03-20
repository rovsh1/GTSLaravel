<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Event\DetailsFieldUpdated;

trait HasFlightNumberTrait
{
    public function flightNumber(): ?string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(?string $flightNumber): void
    {
        $valueBefore = $this->flightNumber;
        $this->flightNumber = $flightNumber;
        $this->pushEvent(new DetailsFieldUpdated($this, 'flightNumber', $flightNumber, $valueBefore));
    }
}
