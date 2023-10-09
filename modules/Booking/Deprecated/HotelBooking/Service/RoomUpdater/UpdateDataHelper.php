<?php

namespace Module\Booking\Deprecated\HotelBooking\Service\RoomUpdater;

use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;

final class UpdateDataHelper
{
    public function __construct(
        public readonly HotelBooking $booking,
        public readonly RoomInfo $roomInfo,
        public readonly GuestIdCollection $guestIds,
        public readonly RoomBookingDetails $details,
        public readonly RoomPrices $price
    ) {}
}
