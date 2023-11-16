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
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\Support\DateTimeImmutableFactory;

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

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->toData(),
            'airportId' => $this->airportId->value(),
            'flightNumber' => $this->flightNumber,
            'departureDate' => $this->departureDate?->getTimestamp(),
            'guestIds' => $this->guestIds->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new CIPSendoffInAirport(
            new DetailsId($data['id']),
            new BookingId($data['bookingId']),
            ServiceInfo::fromData($data['serviceInfo']),
            new AirportId($data['airportId']),
            $data['flightNumber'],
            $data['departureDate'] ? DateTimeImmutableFactory::createFromTimestamp($data['departureDate']) : null,
            GuestIdCollection::fromData($data['guestIds'])
        );
    }
}
