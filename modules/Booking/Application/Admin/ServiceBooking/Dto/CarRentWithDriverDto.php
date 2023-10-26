<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

use Module\Booking\Application\Admin\ServiceBooking\Dto\CarRentWithDriver\BookingPeriodDto;

class CarRentWithDriverDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $cityInfo,
        public readonly ?string $meetingAddress,
        public readonly ?string $meetingTablet,
        public readonly ?BookingPeriodDto $bookingPeriod,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}
