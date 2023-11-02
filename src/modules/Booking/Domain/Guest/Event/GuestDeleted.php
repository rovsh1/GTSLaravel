<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Guest\Event;

use Module\Booking\Domain\Guest\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class GuestDeleted implements DomainEventInterface
{
    public function __construct(
        public readonly GuestId $id
    ) {}
}
