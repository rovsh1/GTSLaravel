<?php

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\RailwayStationId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

class TransferToRailway implements ServiceDetailsInterface
{
    use \Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasTrainNumberTrait;
    use \Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasDepartureDateTrait;
    use \Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasCarBidCollectionTrait;
    use \Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasMeetingTabletTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly RailwayStationId $railwayStationId,
        private ?string $trainNumber,
        private ?string $meetingTablet,
        private ?DateTimeInterface $departureDate,
        private CarBidCollection $carBids
    ) {}

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_TO_AIRPORT;
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
}
