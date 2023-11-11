<?php

namespace Module\Booking\Moderation\Application\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class TestEvent implements IntegrationEventInterface
{
    public function __construct(
        public readonly int $bookingId
    ) {
    }
}