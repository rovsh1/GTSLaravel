<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\Details;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\Details\Concerns\HasCarBidCollectionTrait;
use Sdk\Booking\Entity\Details\Concerns\HasDepartureDateTrait;
use Sdk\Booking\Entity\Details\Concerns\HasMeetingTabletTrait;
use Sdk\Booking\Entity\Details\Concerns\HasTrainNumberTrait;
use Sdk\Booking\Support\Entity\AbstractServiceDetails;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\RailwayStationId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class TransferToRailway extends AbstractServiceDetails implements TransferDetailsInterface
{
    use HasTrainNumberTrait;
    use HasDepartureDateTrait;
    use HasCarBidCollectionTrait;
    use HasMeetingTabletTrait;

    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        private readonly RailwayStationId $railwayStationId,
        private ?string $trainNumber,
        private ?string $meetingTablet,
        private ?DateTimeInterface $departureDate,
        private CarBidCollection $carBids
    ) {
        parent::__construct($id, $bookingId, $serviceInfo);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_TO_AIRPORT;
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
            'departureDate' => $this->departureDate?->getTimestamp(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new TransferToRailway(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new RailwayStationId($payload['railwayStationId']),
            $payload['trainNumber'],
            $payload['meetingTablet'],
            $payload['departureDate'] ? DateTimeImmutableFactory::createFromTimestamp($payload['departureDate']) : null,
            CarBidCollection::fromData($payload['guestIds'])
        );
    }
}
