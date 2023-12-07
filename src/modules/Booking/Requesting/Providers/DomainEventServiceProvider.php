<?php

namespace Module\Booking\Requesting\Providers;

use Module\Booking\Requesting\Domain\Event\BookingRequestSent;
use Module\Booking\Requesting\Domain\Event\CancelRequestSent;
use Module\Booking\Requesting\Domain\Event\ChangeRequestSent;
use Module\Booking\Requesting\Domain\Listener\BookingToWaitingCancellationListener;
use Module\Booking\Requesting\Domain\Listener\BookingToWaitingConfirmationListener;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingRequestSent::class => BookingToWaitingConfirmationListener::class,
        ChangeRequestSent::class => BookingToWaitingConfirmationListener::class,
        CancelRequestSent::class => BookingToWaitingCancellationListener::class,
    ];
}
