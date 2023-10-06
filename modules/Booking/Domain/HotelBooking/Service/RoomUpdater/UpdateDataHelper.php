<?php

namespace Module\Booking\Domain\HotelBooking\Service\RoomUpdater;

use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Booking\Domain\Shared\ValueObject\GuestIdsCollection;

final class UpdateDataHelper
{
    public function __construct(
        public readonly HotelBooking $booking,
        public readonly RoomInfo $roomInfo,
        public readonly GuestIdsCollection $guestIds,
        public readonly RoomBookingDetails $details,
        public readonly RoomPrice $price
    ) {}
}
