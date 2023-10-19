<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

class TransferFromAirportDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly AirportInfoDto $airportInfo,
        public ?string $flightNumber,
        public ?string $meetingTablet,
        public ?string $arrivalDate,
        /** @var CarBidDto[] $carBids */
        public array $carBids
    ) {}
}
