<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasDepartureDateTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasFlightNumberTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasGuestIdCollectionTrait;
use Module\Booking\Shared\Domain\Booking\ValueObject\AirportId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class CIPSendoffInAirport implements AirportDetailsInterface
{
    use HasFlightNumberTrait;
    use HasGuestIdCollectionTrait;
    use HasDepartureDateTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly AirportId $airportId,
        private ?string $flightNumber,
        private ?DateTimeInterface $departureDate,
        private GuestIdCollection $guestIds,
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function airportId(): AirportId
    {
        return $this->airportId;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->serialize(),
            'airportId' => $this->airportId->value(),
            'flightNumber' => $this->flightNumber,
            'departureDate' => $this->departureDate?->getTimestamp(),
            'guestIds' => $this->guestIds->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new CIPSendoffInAirport(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new AirportId($payload['airportId']),
            $payload['flightNumber'],
            $payload['departureDate'] ? DateTimeImmutableFactory::createFromTimestamp($payload['departureDate']) : null,
            GuestIdCollection::deserialize($payload['guestIds'])
        );
    }
}
