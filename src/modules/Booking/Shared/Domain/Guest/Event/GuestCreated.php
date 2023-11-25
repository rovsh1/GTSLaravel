<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Guest\Event;

use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class GuestCreated implements DomainEventInterface
{
    public function __construct(
        public readonly GuestId $id
    ) {}
}
