<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

class OtherService implements ServiceDetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private string $description,
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

    public function description(): string
    {
        return $this->description;
    }
}