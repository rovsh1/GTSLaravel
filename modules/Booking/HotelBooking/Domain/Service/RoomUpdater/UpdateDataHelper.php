<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;

final class UpdateDataHelper
{
    public function __construct(
        public readonly BookingId $bookingId,
        public readonly RoomBookingStatusEnum $status,
        public readonly RoomInfo $roomInfo,
        public readonly GuestCollection $guests,
        public readonly RoomBookingDetails $details,
        public readonly RoomPrice $price
    ) {}
}
