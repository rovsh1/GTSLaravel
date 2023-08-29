<?php

namespace Module\Booking\HotelBooking\Application\Dto\Details;

use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\Order\Application\Response\TouristDto;
use Sdk\Module\Foundation\Support\Dto\Dto;

class RoomBookingDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly int $status,
        public readonly RoomInfoDto $roomInfo,
        /** @var TouristDto[] $guests */
        public readonly array $guests,
        public readonly RoomBookingDetailsDto $details,
        public readonly RoomPriceDto $price
    ) {}
}
