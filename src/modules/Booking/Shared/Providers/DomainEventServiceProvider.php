<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateBookingCancelConditionsListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateHotelQuotaListener;
use Module\Booking\Shared\Domain\Order\Event\OrderCancelled;
use Module\Booking\Shared\Domain\Order\Listener\OrderCancelledListener;
use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\QuotaChangedEventInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        OrderCancelled::class => OrderCancelledListener::class,

        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        QuotaChangedEventInterface::class => UpdateHotelQuotaListener::class,

        CarBidChangedInterface::class => UpdateBookingCancelConditionsListener::class,
    ];
}
