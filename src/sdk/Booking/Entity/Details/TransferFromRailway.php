<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\Details;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\Details\Concerns\HasArrivalDateTrait;
use Sdk\Booking\Entity\Details\Concerns\HasMeetingTabletTrait;
use Sdk\Booking\Entity\Details\Concerns\HasTrainNumberTrait;
use Sdk\Booking\Support\Entity\AbstractServiceDetails;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\RailwayStationId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class TransferFromRailway extends AbstractServiceDetails implements TransferDetailsInterface
{
    use HasTrainNumberTrait;
    use HasMeetingTabletTrait;
    use HasArrivalDateTrait;

    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        private readonly RailwayStationId $railwayStationId,
        private ?string $trainNumber,
        private ?string $meetingTablet,
        private ?DateTimeInterface $arrivalDate,
    ) {
        parent::__construct($id, $bookingId, $serviceInfo);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_FROM_RAILWAY;
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
        );
    }
}
