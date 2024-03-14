<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Event\ServiceDateChanged;
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
        $this->pushEvent(new ServiceDateChanged($this));
    }
}
