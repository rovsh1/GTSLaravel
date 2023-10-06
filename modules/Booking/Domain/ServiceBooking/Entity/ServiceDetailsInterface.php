<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Shared\Enum\ServiceTypeEnum;

interface ServiceDetailsInterface
{
    public function serviceType(): ServiceTypeEnum;

    public function id(): DetailsId;
}