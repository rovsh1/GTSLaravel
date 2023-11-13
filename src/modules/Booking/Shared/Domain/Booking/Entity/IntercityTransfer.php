<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasCarBidCollectionTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasDepartureDateTrait;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\CityId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\Support\DateTimeImmutableFactory;

final class IntercityTransfer implements ServiceDetailsInterface
{
    use HasDepartureDateTrait;
    use HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
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

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function isReturnTripIncluded(): bool
    {
        return $this->returnTripIncluded;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->toData(),
            'fromCityId' => $this->fromCityId->value(),
            'toCityId' => $this->toCityId->value(),
            'returnTripIncluded' => $this->returnTripIncluded,
            'departureDate' => $this->departureDate?->getTimestamp(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new IntercityTransfer(
            new DetailsId($data['id']),
            new BookingId($data['bookingId']),
            ServiceInfo::fromData($data['serviceInfo']),
            new CityId($data['fromCityId']),
            new CityId($data['toCityId']),
            $data['returnTripIncluded'],
            $data['departureDate'] ? DateTimeImmutableFactory::createFromTimestamp($data['departureDate']) : null,
            CarBidCollection::fromData($data['guestIds'])
        );
    }
}
