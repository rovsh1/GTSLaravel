<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Repository;

use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Order\Domain\ValueObject\GuestId;

interface BookingGuestRepositoryInterface
{
    public function bind(RoomBookingId $roomBookingId, GuestId $guestId): void;

    public function unbind(RoomBookingId $roomBookingId, GuestId $guestId): void;
}
