<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Listener\CalculateOtherServiceBookingPricesListener;
use Module\Booking\Shared\Domain\Booking\Listener\PublishIntegrationEventListener;
use Module\Booking\Shared\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateBookingCancelConditionsListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateCarBidCancelConditionsListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateHotelQuotaListener;
use Module\Booking\Shared\Domain\Order\Event\OrderCancelled;
use Module\Booking\Shared\Domain\Order\Listener\CancelOrderInvoiceListener;
use Module\Booking\Shared\Domain\Order\Listener\OrderCancelledListener;
use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\Contracts\Event\CarBidEventInterface;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\QuotaChangedEventInterface;
use Sdk\Booking\Event\BookingCreated;
use Sdk\Booking\Event\BookingDateChangedEventInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingCreated::class => CalculateOtherServiceBookingPricesListener::class,

        OrderCancelled::class => OrderCancelledListener::class,
        InvoiceBecomeDeprecatedEventInterface::class => CancelOrderInvoiceListener::class,

        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        QuotaChangedEventInterface::class => UpdateHotelQuotaListener::class,

        CarBidEventInterface::class => UpdateCarBidCancelConditionsListener::class,
        BookingDateChangedEventInterface::class => UpdateBookingCancelConditionsListener::class,

        BookingEventInterface::class => PublishIntegrationEventListener::class
    ];
}
