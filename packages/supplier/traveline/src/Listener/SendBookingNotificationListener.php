<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Listener;

use Illuminate\Support\Facades\RateLimiter;
use Pkg\Supplier\Traveline\Adapters\TravelineAdapter;
use Pkg\Supplier\Traveline\Models\TravelineReservation;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class SendBookingNotificationListener
{
    public function __construct(
        private readonly TravelineAdapter $travelineAdapter
    ) {}

    public function handle(BookingEventInterface $event): void
    {
        //@todo добавить ретрай при неуспешных запросах + кол-во повторов в конфиг
        //на одно изменение приходит по 2-3 события, делаем запросы в TL с ограничением: 1 запрос в 3 секунд по одной броне.
        RateLimiter::attempt(
            'SendBookingNotificationListener:' . $event->bookingId,
            1,
            function () use ($event) {
                $travelineReservationExists = TravelineReservation::whereReservationId($event->bookingId)->exists();
                if (!$travelineReservationExists) {
                    return;
                }
                \Log::debug('[Traveline] SendBookingNotificationListener Send notification to traveline', ['booking_id' => $event->bookingId]);
                $this->travelineAdapter->sendReservationNotification();
            },
            3,
        );
    }
}
