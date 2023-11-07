<?php

namespace Module\Booking\Shared\Domain\Booking\Entity;

use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

interface ServiceDetailsInterface
{
    public function id(): DetailsId;

    public function serviceType(): ServiceTypeEnum;
}
