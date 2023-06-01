<?php

namespace Module\Booking\Common\Domain\Entity;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;

interface ReservationInterface
{
    public function type(): BookingTypeEnum;

    public function status(): BookingStatusEnum;
}
