<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

use Sdk\Shared\Dto\AirportInfoDto;
use Sdk\Shared\Dto\CityInfoDto;

class CIPSendoffInAirportDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly AirportInfoDto $airportInfo,
        public readonly ?string $flightNumber,
        public readonly ?string $departureDate,
        public readonly array $guestIds,
    ) {}
}
