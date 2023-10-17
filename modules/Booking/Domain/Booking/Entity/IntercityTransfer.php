<?php

namespace Module\Booking\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\CityId;
use Module\Booking\Domain\Booking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

class IntercityTransfer implements ServiceDetailsInterface
{
    use Concerns\HasDepartureDateTrait;
    use Concerns\HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly CityId $fromCityId,
        private readonly CityId $toCityId,
        private readonly bool $returnTripIncluded,
        protected ?DateTimeInterface $departureDate,
        protected CarBidCollection $carBids
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::INTERCITY_TRANSFER;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function fromCityId(): CityId
    {
        return $this->fromCityId;
    }

    public function toCityId(): CityId
    {
        return $this->toCityId;
    }
}
