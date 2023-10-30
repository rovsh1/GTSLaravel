<?php

namespace Module\Booking\Providers;

use Module\Booking\Application\Admin\Shared\Support\IntegrationEventMapper;
use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Booking\Listener\BookingToWaitingCancellationListener;
use Module\Booking\Domain\Booking\Listener\BookingToWaitingConfirmationListener;
use Module\Booking\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Module\Booking\Domain\BookingRequest\Event\BookingRequestSent;
use Module\Booking\Domain\BookingRequest\Event\CancelRequestSent;
use Module\Booking\Domain\BookingRequest\Event\ChangeRequestSent;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected string $integrationEventMapper = IntegrationEventMapper::class;

    protected array $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        BookingRequestSent::class => BookingToWaitingConfirmationListener::class,
        ChangeRequestSent::class => BookingToWaitingConfirmationListener::class,
        CancelRequestSent::class => BookingToWaitingCancellationListener::class,
    ];
}
