<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;

interface QuotaMethodInterface
{
    public function accept(BookingId $id): void;

    public function reserve(RoomBookingId $roomId): void;

    public function cancel(RoomBookingId $roomId): void;
}
