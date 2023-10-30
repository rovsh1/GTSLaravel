<?php

namespace Module\Support\MailManager\Domain\Adapter;

use Module\Booking\Application\Admin\HotelBooking\Dto\BookingDto;

interface HotelBookingAdapterInterface
{
    public function findOrFail(int $bookingId): BookingDto;
}
