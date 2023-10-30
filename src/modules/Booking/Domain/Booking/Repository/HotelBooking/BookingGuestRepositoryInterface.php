<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\HotelBooking;

use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

interface BookingGuestRepositoryInterface
{
    public function bind(RoomBookingId $roomBookingId, GuestId $guestId): void;

    public function unbind(RoomBookingId $roomBookingId, GuestId $guestId): void;
}
