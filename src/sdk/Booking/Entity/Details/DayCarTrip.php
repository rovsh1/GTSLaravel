<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\Details;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\Details\Concerns\HasDepartureDateTrait;
use Sdk\Booking\Support\Entity\AbstractServiceDetails;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CityId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Module\Support\DateTimeImmutable;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class DayCarTrip extends AbstractServiceDetails implements TransferDetailsInterface
{
    use HasDepartureDateTrait;

    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        private readonly CityId $cityId,
        private ?string $destinationsDescription,
        protected ?DateTimeImmutable $departureDate,
    ) {
        parent::__construct($id, $bookingId, $serviceInfo);
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
        );
    }
}
