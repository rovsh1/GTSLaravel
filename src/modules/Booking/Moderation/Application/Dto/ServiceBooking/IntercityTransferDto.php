<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

use Sdk\Shared\Dto\CityInfoDto;

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
