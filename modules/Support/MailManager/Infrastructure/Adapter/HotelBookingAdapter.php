<?php

namespace Module\Support\MailManager\Infrastructure\Adapter;

use Module\Booking\Application\HotelBooking\Dto\BookingDto;
use Module\Booking\Application\HotelBooking\UseCase\Admin\GetBooking;
use Module\Support\MailManager\Domain\Adapter\HotelBookingAdapterInterface;

class HotelBookingAdapter implements HotelBookingAdapterInterface
{
    public function findOrFail(int $bookingId): BookingDto
    {
        return app(GetBooking::class)->execute($bookingId) ?? throw new \Exception("Booking[$bookingId] not found");
    }
}
