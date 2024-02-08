<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Event\DepartureDateChanged;
use Sdk\Module\Support\DateTimeImmutable;

trait HasDepartureDateTrait
{
    public function departureDate(): ?DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?DateTimeImmutable $departureDate): void
    {
        $this->departureDate = $departureDate;
        $this->pushEvent(new DepartureDateChanged($this));
    }

    public function serviceDate(): ?DateTimeImmutable
    {
        return $this->departureDate;
    }
}
