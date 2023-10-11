<?php

namespace Module\Booking\Domain\Booking\Entity;

use Module\Booking\Domain\Booking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

interface ServiceDetailsInterface
{
    public function id(): DetailsId;

    public function serviceType(): ServiceTypeEnum;
}
