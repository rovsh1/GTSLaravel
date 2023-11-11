<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\ChangeHistory\Domain\Listener\BookingChangesListener;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateHotelQuotaListener;
use Module\Booking\Shared\Domain\Shared\Event\BookingEventInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingEventInterface::class => BookingChangesListener::class,
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        QuotaChangedEventInterface::class => UpdateHotelQuotaListener::class,
    ];
}
