<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Guest\Event;

use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Order\Event\OrderGuestEventInterface;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class GuestModified implements DomainEventInterface, OrderGuestEventInterface
{
    public function __construct(
        private readonly Guest $guest,
        private readonly Guest $guestBefore,
    ) {}

    public function guest(): Guest
    {
        return $this->guest;
    }

    public function guestBefore(): Guest
    {
        return $this->guestBefore;
    }

    public function guestId(): GuestId
    {
        return $this->guest->id();
    }
}
