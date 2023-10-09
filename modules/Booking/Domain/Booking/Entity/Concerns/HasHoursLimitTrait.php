<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

trait HasHoursLimitTrait
{
    public function hoursLimit(): ?int
    {
        return $this->hoursLimit;
    }

    public function setHoursLimit(?int $hoursLimit): void
    {
        $this->hoursLimit = $hoursLimit;
    }
}