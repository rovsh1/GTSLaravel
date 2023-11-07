<?php

namespace Module\Booking\Shared\Domain\Booking\Entity;

use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

class OtherService implements ServiceDetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private ?string $description,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::OTHER_SERVICE;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function description(): ?string
    {
        return $this->description;
    }
}
