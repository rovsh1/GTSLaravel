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

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->toData(),
            'cityId' => $this->cityId->value(),
            'destinationsDescription' => $this->destinationsDescription,
            'departureDate' => $this->departureDate?->getTimestamp(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new DayCarTrip(
            new DetailsId($data['id']),
            new BookingId($data['bookingId']),
            ServiceInfo::fromData($data['serviceInfo']),
            new CityId($data['cityId']),
            $data['destinationsDescription'],
            $data['departureDate'] ? DateTimeImmutableFactory::createFromTimestamp($data['departureDate']) : null,
            CarBidCollection::fromData($data['guestIds'])
        );
    }
}
