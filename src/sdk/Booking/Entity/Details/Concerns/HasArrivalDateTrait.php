<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use DateTimeInterface;

trait HasArrivalDateTrait
{
    public function arrivalDate(): ?DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(?DateTimeInterface $arrivalDate): void
    {
        $this->arrivalDate = $arrivalDate;
    }

    public function serviceDate(): ?DateTimeInterface
    {
        return $this->arrivalDate;
    }
}
