<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder\Data\HotelBooking;

use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\HotelBooking\BookingInfoDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\HotelBooking\BookingRoomDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\Support\AbstractData;

final class BookingRequestData extends AbstractData implements DataInterface
{
    /**
     * @param BookingInfoDto $booking
     * @param array<int, BookingRoomDto> $bookingRooms
     */
    public function __construct(
        protected readonly BookingInfoDto $booking,
        protected readonly array $bookingRooms,
    ) {
    }
}