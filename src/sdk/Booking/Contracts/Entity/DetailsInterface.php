<?php

namespace Sdk\Booking\Contracts\Entity;

use DateTimeInterface;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

interface DetailsInterface extends SerializableInterface
{
    public function id(): DetailsId;

    public function serviceType(): ServiceTypeEnum;

    public function serviceDate(): ?DateTimeInterface;
}
