<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Requesting\Domain\BookingRequest\Event\BookingRequestSent;
use Module\Booking\Requesting\Domain\BookingRequest\Event\CancelRequestSent;
use Module\Booking\Requesting\Domain\BookingRequest\Event\ChangeRequestSent;
use Module\Booking\Shared\Application\Support\IntegrationEventMapper;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\Listener\BookingToWaitingCancellationListener;
use Module\Booking\Shared\Domain\Booking\Listener\BookingToWaitingConfirmationListener;
use Module\Booking\Shared\Domain\Booking\Listener\RecalculateBookingPricesListener;
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
