<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Event;

use Module\Booking\Domain\Order\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class GuestCreated implements DomainEventInterface
{
    public function __construct(
        public readonly GuestId $id
    ) {}
}
