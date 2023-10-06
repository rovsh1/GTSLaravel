<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Support\Concerns;
use Module\Booking\Domain\ServiceBooking\ValueObject\AirportId;
use Module\Booking\Domain\ServiceBooking\ValueObject\CarBidCollection;
use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

class TransferFromAirport implements ServiceDetailsInterface
{
    use Concerns\HasMeetingTabletTrait;
    use Concerns\HasFlightNumberTrait;
    use Concerns\HasArrivalDateTrait;
    use Concerns\HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly AirportId $airportId,
        protected string $flightNumber,
        protected DateTimeInterface $arrivalDate,
        protected string $meetingTablet,
        protected CarBidCollection $carBids
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_TO_AIRPORT;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function airportId(): AirportId
    {
        return $this->airportId;
    }
}