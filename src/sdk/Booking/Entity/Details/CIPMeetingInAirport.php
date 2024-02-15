<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\Details;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\AirportDetailsInterface;
use Sdk\Booking\Entity\Details\Concerns\HasArrivalDateTrait;
use Sdk\Booking\Entity\Details\Concerns\HasFlightNumberTrait;
use Sdk\Booking\Entity\Details\Concerns\HasGuestIdCollectionTrait;
use Sdk\Booking\Support\Entity\AbstractServiceDetails;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Module\Support\DateTimeImmutable;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class CIPMeetingInAirport extends AbstractServiceDetails implements AirportDetailsInterface
{
    use HasFlightNumberTrait;
    use HasGuestIdCollectionTrait;
    use HasArrivalDateTrait;

    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        private readonly AirportId $airportId,
        private ?string $flightNumber,
        private ?DateTimeImmutable $arrivalDate,
        private GuestIdCollection $guestIds,
    ) {
        parent::__construct($id, $bookingId, $serviceInfo);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::CIP_MEETING_IN_AIRPORT;
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
