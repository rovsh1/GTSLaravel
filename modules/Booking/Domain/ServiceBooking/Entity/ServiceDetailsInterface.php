<?php

namespace Module\Booking\Domain\ServiceBooking\Entity;

use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;

interface ServiceDetailsInterface
{
    public function id(): DetailsId;

    public function serviceInfo(): ServiceInfo;
}
