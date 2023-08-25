<?php

declare(strict_types=1);

namespace Module\Booking\Tourist\Domain\Event;

use Module\Booking\Tourist\Domain\Tourist;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class TouristCreated implements DomainEventInterface
{
    public function __construct(
        public readonly Tourist $tourist
    ) {}
}
