<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto\ServiceBooking;

class TransferFromRailwayDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $city,
        public readonly RailwayStationInfoDto $railwayInfo,
        public readonly ?string $trainNumber,
        public readonly ?string $meetingTablet,
        public readonly ?string $arrivalDate,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}
