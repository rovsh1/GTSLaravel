<?php

namespace Module\Booking\Application\Admin\Shared\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class TestEvent implements IntegrationEventInterface
{
    public function __construct(
        public readonly int $bookingId
    ) {
    }
}