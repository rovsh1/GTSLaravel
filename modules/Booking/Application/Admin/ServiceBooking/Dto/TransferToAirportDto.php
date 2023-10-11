<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

class TransferToAirportDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $serviceTitle,
        public readonly int $airportId,
        public ?string $flightNumber,
        public ?string $departureDate,
        public array $carBids
    ) {}
}
