<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateData\HotelBooking;

use Module\Booking\Invoicing\Domain\Service\Dto\Booking\BookingPeriodDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\HotelBooking\HotelInfoDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateDataInterface;

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
