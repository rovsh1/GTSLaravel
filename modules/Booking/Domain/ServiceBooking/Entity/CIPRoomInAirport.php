<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Support\Concerns;
use Module\Booking\Domain\ServiceBooking\ValueObject\AirportId;
use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Enum\ServiceTypeEnum;

class CIPRoomInAirport implements ServiceDetailsInterface
{
    use Concerns\HasFlightNumberTrait;
    use Concerns\HasGuestIdCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly AirportId $airportId,
        protected string $flightNumber,
        protected DateTimeInterface $serviceDate,
        private GuestIdCollection $guestIds,
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
