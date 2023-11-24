<?php

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Shared\Contracts\Support\SerializableInterface;
use Module\Shared\Enum\ServiceTypeEnum;

interface DetailsInterface extends SerializableInterface
{
    public function id(): DetailsId;

    public function serviceType(): ServiceTypeEnum;

    public function serviceDate(): ?DateTimeInterface;
}
