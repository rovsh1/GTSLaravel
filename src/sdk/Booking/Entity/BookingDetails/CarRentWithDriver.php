<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\BookingDetails;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasBookingPeriodTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasCarBidCollectionTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasMeetingAddressTrait;
use Sdk\Booking\Entity\BookingDetails\Concerns\HasMeetingTabletTrait;
use Sdk\Booking\Support\Entity\AbstractServiceDetails;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPeriod;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\CityId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;

final class CarRentWithDriver extends AbstractServiceDetails implements TransferDetailsInterface
{
    use HasBookingPeriodTrait;
    use HasCarBidCollectionTrait;
    use HasMeetingTabletTrait;
    use HasMeetingAddressTrait;

    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        private readonly CityId $cityId,
        private ?string $meetingAddress,
        private ?string $meetingTablet,
        protected ?BookingPeriod $bookingPeriod,
        protected CarBidCollection $carBids
    ) {
        parent::__construct($id, $bookingId, $serviceInfo);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::CAR_RENT_WITH_DRIVER;
    }

    public function cityId(): CityId
    {
        return $this->cityId;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->serialize(),
            'cityId' => $this->cityId->value(),
            'meetingAddress' => $this->meetingAddress,
            'meetingTablet' => $this->meetingTablet,
            'bookingPeriod' => $this->bookingPeriod?->serialize(),
            'carBids' => $this->carBids->toData(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new CarRentWithDriver(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            new CityId($payload['cityId']),
            $payload['meetingAddress'],
            $payload['meetingTablet'],
            $payload['bookingPeriod'] ? BookingPeriod::deserialize($payload['bookingPeriod']) : null,
            CarBidCollection::fromData($payload['guestIds'])
        );
    }

    public function serviceDate(): ?DateTimeInterface
    {
        return $this->bookingPeriod()?->dateFrom();
    }
}
