<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\BookingDetails;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\AirportDetailsInterface;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasArrivalDateTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasFlightNumberTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasGuestIdCollectionTrait;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class CIPMeetingInAirport implements AirportDetailsInterface
{
    use HasFlightNumberTrait;
    use HasGuestIdCollectionTrait;
    use HasArrivalDateTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly AirportId $airportId,
        private ?string $flightNumber,
        private ?DateTimeInterface $arrivalDate,
        private GuestIdCollection $guestIds,
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::CIP_MEETING_IN_AIRPORT;
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
            'arrivalDate' => $this->arrivalDate?->getTimestamp(),
            'guestIds' => $this->guestIds->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new CIPMeetingInAirport(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new AirportId($payload['airportId']),
            $payload['flightNumber'],
            $payload['arrivalDate'] ? DateTimeImmutableFactory::createFromTimestamp($payload['arrivalDate']) : null,
            GuestIdCollection::deserialize($payload['guestIds'])
        );
    }
}
