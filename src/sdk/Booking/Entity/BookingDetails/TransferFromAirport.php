<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\BookingDetails;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasArrivalDateTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasCarBidCollectionTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasFlightNumberTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasMeetingTabletTrait;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class TransferFromAirport implements TransferDetailsInterface
{
    use HasMeetingTabletTrait;
    use HasFlightNumberTrait;
    use HasArrivalDateTrait;
    use HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly AirportId $airportId,
        private ?string $flightNumber,
        private ?string $meetingTablet,
        private ?DateTimeInterface $arrivalDate,
        private CarBidCollection $carBids
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_FROM_AIRPORT;
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
            'meetingTablet' => $this->meetingTablet,
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new TransferFromAirport(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new AirportId($payload['airportId']),
            $payload['flightNumber'],
            $payload['meetingTablet'],
            $payload['arrivalDate'] ? DateTimeImmutableFactory::createFromTimestamp($payload['arrivalDate']) : null,
            CarBidCollection::fromData($payload['guestIds'])
        );
    }
}
