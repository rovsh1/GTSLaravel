<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Repository;

use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Domain\Order\ValueObject\GuestId;

interface BookingGuestRepositoryInterface
{
    public function bind(RoomBookingId $roomBookingId, GuestId $guestId): void;

    public function unbind(RoomBookingId $roomBookingId, GuestId $guestId): void;
}
