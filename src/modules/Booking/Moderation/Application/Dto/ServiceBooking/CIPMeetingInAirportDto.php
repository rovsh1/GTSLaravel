<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

use Module\Shared\Dto\AirportInfoDto;
use Module\Shared\Dto\CityInfoDto;

class CIPMeetingInAirportDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly AirportInfoDto $airportInfo,
        public readonly ?string $flightNumber,
        public readonly ?string $arrivalDate,
        public readonly array $guestIds,
    ) {}
}
