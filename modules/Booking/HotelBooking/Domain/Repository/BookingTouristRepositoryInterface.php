<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Repository;

use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Order\Domain\ValueObject\TouristId;

interface BookingTouristRepositoryInterface
{
    public function bind(RoomBookingId $roomBookingId, TouristId $touristId): void;

    public function unbind(RoomBookingId $roomBookingId, TouristId $touristId): void;
}
