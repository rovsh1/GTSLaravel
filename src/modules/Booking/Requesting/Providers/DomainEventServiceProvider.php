<?php

namespace Module\Booking\Requesting\Providers;

use Module\Booking\Requesting\Domain\BookingRequest\Event\BookingRequestSent;
use Module\Booking\Requesting\Domain\BookingRequest\Event\CancelRequestSent;
use Module\Booking\Requesting\Domain\BookingRequest\Event\ChangeRequestSent;
use Module\Booking\Requesting\Domain\BookingRequest\Listener\BookingToWaitingCancellationListener;
use Module\Booking\Requesting\Domain\BookingRequest\Listener\BookingToWaitingConfirmationListener;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingRequestSent::class => BookingToWaitingConfirmationListener::class,
        ChangeRequestSent::class => BookingToWaitingConfirmationListener::class,
        CancelRequestSent::class => BookingToWaitingCancellationListener::class,
    ];
}
