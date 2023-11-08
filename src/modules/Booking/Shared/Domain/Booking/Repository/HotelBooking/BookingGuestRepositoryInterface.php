<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\HotelBooking;

use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;

interface BookingGuestRepositoryInterface
{
    public function bind(RoomBookingId $roomBookingId, GuestId $guestId): void;

    public function unbind(RoomBookingId $roomBookingId, GuestId $guestId): void;
}
