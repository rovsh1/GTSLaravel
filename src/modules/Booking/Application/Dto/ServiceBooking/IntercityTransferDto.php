<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto\ServiceBooking;

class IntercityTransferDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CityInfoDto $fromCity,
        public readonly CityInfoDto $toCity,
        public readonly bool $returnTripIncluded,
        public readonly ?string $departureDate,
        /** @var CarBidDto[] $carBids */
        public readonly array $carBids
    ) {}
}