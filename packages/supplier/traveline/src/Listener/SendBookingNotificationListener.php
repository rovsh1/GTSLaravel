<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Listener;

use Illuminate\Support\Facades\RateLimiter;
use Pkg\Supplier\Traveline\Adapters\TravelineAdapter;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class SendBookingNotificationListener
{
    public function __construct(
        private readonly TravelineAdapter $travelineAdapter
    ) {}

    public function handle(BookingEventInterface $event): void
    {
        //на одно изменение приходит по 2-3 события, делаем запросы в TL с ограничением: 1 запрос в 5 секунд по одной броне.
        RateLimiter::attempt(
            'SendBookingNotificationListener:' . $event->bookingId,
            1,
            function () use ($event) {
                \Log::debug('TRAVELINE event', ['event' => $event]);
                $this->travelineAdapter->sendReservationNotification();
            },
            5,
        );
    }
}
