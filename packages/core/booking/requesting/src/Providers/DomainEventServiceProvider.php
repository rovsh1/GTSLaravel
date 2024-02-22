<?php

namespace Pkg\Booking\Requesting\Providers;

use Pkg\Booking\Requesting\Domain\Event\BookingRequestEventInterface;
use Pkg\Booking\Requesting\Domain\Event\BookingRequestSent;
use Pkg\Booking\Requesting\Domain\Event\CancelRequestSent;
use Pkg\Booking\Requesting\Domain\Event\ChangeRequestSent;
use Pkg\Booking\Requesting\Domain\Listener\SendMailNotificationsListener;
use Pkg\Booking\Requesting\Domain\Listener\BookingToWaitingCancellationListener;
use Pkg\Booking\Requesting\Domain\Listener\BookingToWaitingConfirmationListener;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingRequestSent::class => BookingToWaitingConfirmationListener::class,
        ChangeRequestSent::class => BookingToWaitingConfirmationListener::class,
        CancelRequestSent::class => BookingToWaitingCancellationListener::class,

        BookingRequestEventInterface::class => SendMailNotificationsListener::class,
    ];
}
