<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface QuotaChangedEventInterface extends BookingEventInterface,
                                             PriceBecomeDeprecatedEventInterface,
                                             IntegrationEventInterface
{
}
