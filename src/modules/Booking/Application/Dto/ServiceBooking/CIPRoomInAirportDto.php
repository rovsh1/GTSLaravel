<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto\ServiceBooking;

class CIPRoomInAirportDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly AirportInfoDto $airportInfo,
        public readonly ?string $flightNumber,
        public readonly ?string $serviceDate,
        public readonly array $guestIds,
    ) {}
}
