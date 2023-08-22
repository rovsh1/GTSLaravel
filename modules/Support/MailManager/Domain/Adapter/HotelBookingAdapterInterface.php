<?php

namespace Module\Support\MailManager\Domain\Adapter;

use Module\Booking\HotelBooking\Application\Dto\BookingDto;

interface HotelBookingAdapterInterface
{
    public function findOrFail(int $bookingId): BookingDto;
}