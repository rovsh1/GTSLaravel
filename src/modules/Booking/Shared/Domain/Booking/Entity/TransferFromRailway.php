<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasArrivalDateTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasCarBidCollectionTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasMeetingTabletTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasTrainNumberTrait;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\RailwayStationId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\Support\DateTimeImmutableFactory;

final class TransferFromRailway implements ServiceDetailsInterface
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

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->toData(),
            'railwayStationId' => $this->railwayStationId->value(),
            'trainNumber' => $this->trainNumber,
            'meetingTablet' => $this->meetingTablet,
            'arrivalDate' => $this->arrivalDate?->getTimestamp(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new TransferFromRailway(
            new DetailsId($data['id']),
            new BookingId($data['bookingId']),
            ServiceInfo::fromData($data['serviceInfo']),
            new RailwayStationId($data['railwayStationId']),
            $data['trainNumber'],
            $data['meetingTablet'],
            $data['arrivalDate'] ? DateTimeImmutableFactory::createFromTimestamp($data['arrivalDate']) : null,
            CarBidCollection::fromData($data['guestIds'])
        );
    }
}
