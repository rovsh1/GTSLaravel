<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Event;

use Module\Booking\Order\Domain\ValueObject\TouristId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class TouristDeleted implements DomainEventInterface
{
    public function __construct(
        public readonly TouristId $id
    ) {}
}
