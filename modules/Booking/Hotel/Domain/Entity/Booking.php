<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Booking\Common\Domain\Entity\Booking as Common;
use Module\Booking\Common\Domain\Entity\Details\BookingDetailsInterface;

class Booking extends Common
{
    public function details(): BookingDetailsInterface
    {
        // TODO: Implement details() method.
    }
}
