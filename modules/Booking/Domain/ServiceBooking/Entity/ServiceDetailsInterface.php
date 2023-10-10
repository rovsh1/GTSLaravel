<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

interface ServiceDetailsInterface
{
    public function id(): DetailsId;

    public function serviceType(): ServiceTypeEnum;
}
