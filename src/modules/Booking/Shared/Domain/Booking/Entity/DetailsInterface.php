<?php

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\ServiceTypeEnum;

interface DetailsInterface extends SerializableDataInterface
{
    public function id(): DetailsId;

    public function serviceType(): ServiceTypeEnum;

    public function serviceDate(): ?DateTimeInterface;
}
