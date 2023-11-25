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
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class DayCarTrip implements TransferDetailsInterface
{
    use HasDepartureDateTrait;
    use HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly CityId $cityId,
        private ?string $destinationsDescription,
        protected ?DateTimeInterface $departureDate,
        protected CarBidCollection $carBids
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::DAY_CAR_TRIP;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function cityId(): CityId
    {
        return $this->cityId;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function destinationsDescription(): ?string
    {
        return $this->destinationsDescription;
    }

    public function setDestinationsDescription(?string $destinationsDescription): void
    {
        $this->destinationsDescription = $destinationsDescription;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->serialize(),
            'cityId' => $this->cityId->value(),
            'destinationsDescription' => $this->destinationsDescription,
            'departureDate' => $this->departureDate?->getTimestamp(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new DayCarTrip(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new CityId($payload['cityId']),
            $payload['destinationsDescription'],
            $payload['departureDate'] ? DateTimeImmutableFactory::createFromTimestamp($payload['departureDate']) : null,
            CarBidCollection::fromData($payload['guestIds'])
        );
    }
}
