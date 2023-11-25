<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

use Module\Booking\Moderation\Application\Dto\ServiceBooking\CarRentWithDriver\BookingPeriodDto;
use Sdk\Shared\Dto\CityInfoDto;

class CarRentWithDriverDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly ?string $meetingAddress,
        public readonly ?string $meetingTablet,
        public readonly ?BookingPeriodDto $bookingPeriod,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}
