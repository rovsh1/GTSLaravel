<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Guest\Event;

use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class GuestDeleted implements DomainEventInterface
{
    public function __construct(
        public readonly GuestId $id
    ) {}
}
