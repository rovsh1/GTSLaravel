<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Event\Contracts;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): int;
}
