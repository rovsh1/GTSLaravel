<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use DateTimeInterface;
use Sdk\Booking\Event\DepartureDateChanged;

trait HasDepartureDateTrait
{
    public function departureDate(): ?DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?DateTimeInterface $departureDate): void
    {
        $this->departureDate = $departureDate;
        $this->pushEvent(new DepartureDateChanged($this));
    }

    public function serviceDate(): ?DateTimeInterface
    {
        return $this->departureDate;
    }
}
