<?php

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasArrivalDateTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasCarBidCollectionTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasFlightNumberTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasMeetingTabletTrait;
use Module\Booking\Shared\Domain\Booking\ValueObject\AirportId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

class TransferFromAirport implements ServiceDetailsInterface
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
        private ?DateTimeInterface $arrivalDate,
        private ?string $meetingTablet,
        private CarBidCollection $carBids
    ) {}

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
}
