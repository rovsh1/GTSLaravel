<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

class CarRentWithDriverDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly ?string $meetingAddress,
        public readonly ?string $meetingTablet,
        public readonly ?int $hoursLimit,
        public readonly ?string $date,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}
