<?php

namespace Pkg\Booking\Requesting\Domain\Service\TemplateData\HotelBooking;

use Pkg\Booking\Requesting\Domain\Service\Dto\HotelBooking\BookingPeriodDto;
use Pkg\Booking\Requesting\Domain\Service\Dto\HotelBooking\HotelInfoDto;
use Pkg\Booking\Requesting\Domain\Service\TemplateDataInterface;

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
