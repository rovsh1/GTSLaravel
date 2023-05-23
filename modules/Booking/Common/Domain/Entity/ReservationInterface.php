<?php

namespace Module\Booking\Common\Domain\Entity;

use Module\Booking\Common\Domain\Entity\Details\BookingDetailsInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

interface ReservationInterface
{
    public function status(): BookingStatusEnum;
}
