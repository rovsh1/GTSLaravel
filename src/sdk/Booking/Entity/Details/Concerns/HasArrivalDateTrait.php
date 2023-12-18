<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use DateTimeInterface;
use Sdk\Booking\Event\ArrivalDateChanged;

trait HasArrivalDateTrait
{
    public function arrivalDate(): ?DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(?DateTimeInterface $arrivalDate): void
    {
        $this->arrivalDate = $arrivalDate;
        $this->pushEvent(new ArrivalDateChanged($this));
    }

    public function serviceDate(): ?DateTimeInterface
    {
        return $this->arrivalDate;
    }
}
