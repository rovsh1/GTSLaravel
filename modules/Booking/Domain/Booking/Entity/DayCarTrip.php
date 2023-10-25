<?php

namespace Module\Booking\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\CityId;
use Module\Booking\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

class DayCarTrip implements ServiceDetailsInterface
{
    use Concerns\HasDateTrait;
    use Concerns\HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly CityId $cityId,
        private ?string $destinationsDescription,
        protected ?DateTimeInterface $date,
        protected CarBidCollection $carBids
    ) {}

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::DAY_CAR_TRIP;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function cityId(): CityId
    {
        return $this->cityId;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function destinationsDescription(): ?string
    {
        return $this->destinationsDescription;
    }

    public function setDestinationsDescription(?string $destinationsDescription): void
    {
        $this->destinationsDescription = $destinationsDescription;
    }
}
