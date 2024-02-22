<?php

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\HotelBooking;

use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\HotelBooking\BookingPeriodDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\HotelBooking\HotelInfoDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TemplateDataInterface;

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
