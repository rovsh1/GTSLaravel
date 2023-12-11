<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Event;

use Sdk\Booking\ValueObject\GuestId;

interface OrderGuestEventInterface
{
    public function guestId(): GuestId;
}
