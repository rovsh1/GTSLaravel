<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;

interface QuotaChangedEventInterface
{
    public function booking(): Booking;
}
