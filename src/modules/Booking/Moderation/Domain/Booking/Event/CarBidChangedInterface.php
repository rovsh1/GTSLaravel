<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface CarBidChangedInterface extends BookingEventInterface,
                                         PriceBecomeDeprecatedEventInterface,
                                         IntegrationEventInterface
{
}