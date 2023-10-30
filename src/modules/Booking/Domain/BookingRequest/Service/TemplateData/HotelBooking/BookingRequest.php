<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData\HotelBooking;

use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\BookingPeriodDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\HotelInfoDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;

final class BookingRequest implements TemplateDataInterface
{
    public function __construct(
        private readonly array $rooms,
        private readonly HotelInfoDto $hotelInfo,
        private readonly BookingPeriodDto $bookingPeriod,
    ) {}

    public function toArray(): array
    {
        return [
            'rooms' => $this->rooms,
            'hotel' => $this->hotelInfo,
            'bookingPeriod' => $this->bookingPeriod,
        ];
    }
}
