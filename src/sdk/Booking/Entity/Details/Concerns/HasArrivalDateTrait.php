<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Event\ArrivalDateChanged;
use Sdk\Module\Support\DateTimeImmutable;

trait HasArrivalDateTrait
{
    public function arrivalDate(): ?DateTimeImmutable
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(?DateTimeImmutable $arrivalDate): void
    {
        $this->arrivalDate = $arrivalDate;
        $this->pushEvent(new ArrivalDateChanged($this));
    }

    public function serviceDate(): ?DateTimeImmutable
    {
        return $this->arrivalDate;
    }
}
