<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

trait HasTrainNumberTrait
{
    public function trainNumber(): ?string
    {
        return $this->trainNumber;
    }

    public function setTrainNumber(?string $trainNumber): void
    {
        $this->trainNumber = $trainNumber;
    }
}
