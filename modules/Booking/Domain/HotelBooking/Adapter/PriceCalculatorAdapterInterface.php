<?php

namespace Module\Booking\Domain\HotelBooking\Adapter;

interface PriceCalculatorAdapterInterface
{
    public function recalculate(int $bookingId): void;
}
