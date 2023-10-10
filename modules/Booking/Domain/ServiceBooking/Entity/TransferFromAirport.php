<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Support\Concerns;
use Module\Booking\Domain\ServiceBooking\ValueObject\AirportId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\CarBidCollection;
use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

class TransferFromAirport implements ServiceDetailsInterface
{
    use Concerns\HasMeetingTabletTrait;
    use Concerns\HasFlightNumberTrait;
    use Concerns\HasArrivalDateTrait;
    use Concerns\HasCarBidCollectionTrait;

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
