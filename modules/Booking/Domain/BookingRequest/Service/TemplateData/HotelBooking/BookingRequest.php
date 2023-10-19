<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData\HotelBooking;

use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\BookingDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;

final class BookingRequest implements TemplateDataInterface
{
    public function __construct(
        private readonly BookingDto $bookingDto,
        private readonly array $roomsDto,
    ) {}

    public function toArray(): array
    {
        return [
            'booking' => $this->bookingDto,
            'rooms' => $this->roomsDto,
        ];
    }
}
