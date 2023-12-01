<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\ValueObject\BookingPeriod;

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
