<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking;

enum RoomBookingStatusEnum: int
{
    case WAITING_CONFIRMATION = 1;
    case CANCELLED = 2;
    case CONFIRMED = 3;
}
