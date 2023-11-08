<?php

namespace Module\Booking\Shared\Domain\Booking\Entity\Concerns;

use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPeriod;

trait HasBookingPeriodTrait
{
    public function bookingPeriod(): ?BookingPeriod
    {
        return $this->bookingPeriod;
    }

    public function setBookingPeriod(?BookingPeriod $period): void
    {
        $this->bookingPeriod = $period;
    }
}
