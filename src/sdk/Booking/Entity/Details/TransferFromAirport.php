<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\Details;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\Details\Concerns\HasArrivalDateTrait;
use Sdk\Booking\Entity\Details\Concerns\HasFlightNumberTrait;
use Sdk\Booking\Entity\Details\Concerns\HasMeetingTabletTrait;
use Sdk\Booking\Support\Entity\AbstractServiceDetails;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class TransferFromAirport extends AbstractServiceDetails implements TransferDetailsInterface
{
    use HasMeetingTabletTrait;
    use HasFlightNumberTrait;
    use HasArrivalDateTrait;

    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        private readonly AirportId $airportId,
        private ?string $flightNumber,
        private ?string $meetingTablet,
        private ?DateTimeInterface $arrivalDate,
    ) {
        parent::__construct($id, $bookingId, $serviceInfo);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_FROM_AIRPORT;
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
        );
    }
}
