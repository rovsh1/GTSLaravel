<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateHotelQuotaListener;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        QuotaChangedEventInterface::class => UpdateHotelQuotaListener::class,
    ];
}
