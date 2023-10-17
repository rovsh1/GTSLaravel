<?php

namespace Module\Booking\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\CityId;
use Module\Booking\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

class CarRentWithDriver implements ServiceDetailsInterface
{
    use Concerns\HasDateTrait;
    use Concerns\HasHoursLimitTrait;
    use Concerns\HasCarBidCollectionTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly CityId $cityId,
        private ?int $hoursLimit,
        protected ?DateTimeInterface $date,
        protected CarBidCollection $carBids
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::CAR_RENT_WITH_DRIVER;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function cityId(): CityId
    {
        return $this->cityId;
    }
}
