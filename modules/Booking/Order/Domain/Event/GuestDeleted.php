<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Event;

use Module\Booking\Order\Domain\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class GuestDeleted implements DomainEventInterface
{
    public function __construct(
        public readonly GuestId $id
    ) {}
}
