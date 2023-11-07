<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto\ServiceBooking;

use Module\Shared\Dto\CityInfoDto;

class DayCarTripDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly ?string $departureDate,
        public readonly ?string $destinationsDescription,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}
