<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

class OtherService implements ServiceDetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly string $serviceTitle,
        private string $description,
    ) {
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function serviceTitle(): string
    {
        return $this->serviceTitle;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::TRANSFER_TO_AIRPORT;
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
