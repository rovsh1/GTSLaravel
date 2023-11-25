<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\BookingDetails;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasArrivalDateTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasCarBidCollectionTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasMeetingTabletTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasTrainNumberTrait;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\RailwayStationId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class TransferFromRailway implements TransferDetailsInterface
{
    use HasTrainNumberTrait;
    use HasMeetingTabletTrait;
    use HasArrivalDateTrait;
    use HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly RailwayStationId $railwayStationId,
        private ?string $trainNumber,
        private ?string $meetingTablet,
        private ?DateTimeInterface $arrivalDate,
        private CarBidCollection $carBids
    ) {
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_FROM_AIRPORT;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function railwayStationId(): RailwayStationId
    {
        return $this->railwayStationId;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->serialize(),
            'railwayStationId' => $this->railwayStationId->value(),
            'trainNumber' => $this->trainNumber,
            'meetingTablet' => $this->meetingTablet,
            'arrivalDate' => $this->arrivalDate?->getTimestamp(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new TransferFromRailway(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new RailwayStationId($payload['railwayStationId']),
            $payload['trainNumber'],
            $payload['meetingTablet'],
            $payload['arrivalDate'] ? DateTimeImmutableFactory::createFromTimestamp($payload['arrivalDate']) : null,
            CarBidCollection::fromData($payload['guestIds'])
        );
    }
}
