<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\BookingDetails;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasCarBidCollectionTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasDepartureDateTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasFlightNumberTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasMeetingTabletTrait;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class TransferToAirport implements TransferDetailsInterface
{
    use HasFlightNumberTrait;
    use HasDepartureDateTrait;
    use HasCarBidCollectionTrait;
    use HasMeetingTabletTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly AirportId $airportId,
        private ?string $flightNumber,
        private ?string $meetingTablet,
        private ?DateTimeInterface $departureDate,
        private CarBidCollection $carBids
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_TO_AIRPORT;
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
            'meetingTablet' => $this->meetingTablet,
            'departureDate' => $this->departureDate?->getTimestamp(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new TransferToAirport(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new AirportId($payload['airportId']),
            $payload['flightNumber'],
            $payload['meetingTablet'],
            $payload['departureDate'] ? DateTimeImmutableFactory::createFromTimestamp($payload['departureDate']) : null,
            CarBidCollection::fromData($payload['guestIds'])
        );
    }
}
