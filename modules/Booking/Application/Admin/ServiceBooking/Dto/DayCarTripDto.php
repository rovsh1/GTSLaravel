<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

class DayCarTripDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly ?string $date,
        public readonly ?string $destinationsDescription,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}
