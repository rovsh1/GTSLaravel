<?php

namespace Module\Booking\HotelBooking\Application\Dto\Details;

use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\Order\Application\Response\GuestDto;
use Sdk\Module\Foundation\Support\Dto\Dto;

class RoomBookingDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly RoomInfoDto $roomInfo,
        /** @var int[] $guestIds */
        public readonly array $guestIds,
        public readonly RoomBookingDetailsDto $details,
        public readonly RoomPriceDto $price
    ) {}
}
