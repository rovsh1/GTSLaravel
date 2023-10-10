<?php

namespace Module\Booking\Domain\ServiceBooking\Support\Concerns;

trait HasHoursLimitTrait
{
    public function hoursLimit(): int
    {
        return $this->hoursLimit;
    }

    public function setHoursLimit(int $hoursLimit): void
    {
        $this->hoursLimit = $hoursLimit;
    }
}