<?php

declare(strict_types=1);

namespace Sdk\Booking\Event;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\Enum\StatusEnum;

interface BookingCancelledEventInterface extends BookingEventInterface
{
    public function status(): StatusEnum;
}
