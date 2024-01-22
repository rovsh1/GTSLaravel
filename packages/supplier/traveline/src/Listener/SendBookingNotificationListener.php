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
        //@todo добавить ретрай при неуспешных запросах + кол-во повторов в конфиг
        //на одно изменение приходит по 2-3 события, делаем запросы в TL с ограничением: 1 запрос в 5 секунд по одной броне.
        RateLimiter::attempt(
            'SendBookingNotificationListener:' . $event->bookingId,
            1,
            function () use ($event) {
                \Log::debug('[Traveline] SendBookingNotificationListener', ['event' => $event]);
                $this->travelineAdapter->sendReservationNotification();
            },
            2,
        );
    }
}
