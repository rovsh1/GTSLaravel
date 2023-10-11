<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

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
}
