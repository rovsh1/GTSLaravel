<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Event;

use Module\Booking\Shared\Domain\Booking\Event\BookingEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface BookingRequestEventInterface extends BookingEventInterface, IntegrationEventInterface
{
}
