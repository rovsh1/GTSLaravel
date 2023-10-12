<?php

namespace Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;

final class UpdateDataHelper
{
    public function __construct(
        public readonly Booking $booking,
        public readonly HotelBooking $bookingDetails,
        public readonly RoomInfo $roomInfo,
        public readonly GuestIdCollection $guestIds,
        public readonly RoomBookingDetails $roomDetails,
        public readonly RoomPrices $price
    ) {
    }
}
