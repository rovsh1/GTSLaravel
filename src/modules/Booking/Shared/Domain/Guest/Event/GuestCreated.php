<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Guest\Event;

use Module\Booking\Shared\Domain\Order\Event\OrderGuestEventInterface;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class GuestCreated implements DomainEventInterface, OrderGuestEventInterface
{
    public function __construct(
        public readonly GuestId $guestId,
    ) {}

    public function guestId(): GuestId
    {
        return $this->guestId;
    }
}
