<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

class OtherService implements ServiceDetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private string $description,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::OTHER;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }
}
