<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Listener;

use Illuminate\Support\Facades\RateLimiter;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class SendBookingNotificationListener
{
    public function handle(BookingEventInterface $event): void
    {
        RateLimiter::attempt(
            'SendBookingNotificationListener:' . $event->bookingId,
            1,
            function () use ($event) {
                \Log::debug('TRAVELINE event', ['event' => $event]);
            },
            5,
        );
    }
}
