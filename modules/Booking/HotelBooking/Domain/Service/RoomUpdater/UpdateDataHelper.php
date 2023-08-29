<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater;

use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Module\Booking\Order\Domain\ValueObject\TouristIdsCollection;

final class UpdateDataHelper
{
    public function __construct(
        public readonly Booking $booking,
        public readonly RoomBookingStatusEnum $status,
        public readonly RoomInfo $roomInfo,
        public readonly TouristIdsCollection $guestIds,
        public readonly RoomBookingDetails $details,
        public readonly RoomPrice $price
    ) {}
}
