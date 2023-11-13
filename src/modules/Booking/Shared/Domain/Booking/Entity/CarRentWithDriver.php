<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity;

use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasBookingPeriodTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasCarBidCollectionTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasMeetingAddressTrait;
use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasMeetingTabletTrait;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\CityId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

final class CarRentWithDriver implements ServiceDetailsInterface
{
    use HasBookingPeriodTrait;
    use HasCarBidCollectionTrait;
    use HasMeetingTabletTrait;
    use HasMeetingAddressTrait;

    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private readonly CityId $cityId,
        private ?string $meetingAddress,
        private ?string $meetingTablet,
        protected ?BookingPeriod $bookingPeriod,
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

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->toData(),
            'cityId' => $this->cityId->value(),
            'meetingAddress' => $this->meetingAddress,
            'meetingTablet' => $this->meetingTablet,
            'bookingPeriod' => $this->bookingPeriod?->toData(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new CarRentWithDriver(
            new DetailsId($data['id']),
            new BookingId($data['bookingId']),
            ServiceInfo::fromData($data['serviceInfo']),
            new CityId($data['cityId']),
            $data['meetingAddress'],
            $data['meetingTablet'],
            $data['bookingPeriod'] ? BookingPeriod::fromData($data['bookingPeriod']) : null,
            CarBidCollection::fromData($data['guestIds'])
        );
    }
}
