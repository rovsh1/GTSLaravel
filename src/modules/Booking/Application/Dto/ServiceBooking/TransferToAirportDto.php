<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto\ServiceBooking;

class TransferToAirportDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly AirportInfoDto $airportInfo,
        public readonly ?string $flightNumber,
        public readonly ?string $meetingTablet,
        public readonly ?string $departureDate,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}
