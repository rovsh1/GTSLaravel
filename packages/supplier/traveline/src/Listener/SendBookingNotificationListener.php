<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Listener;

use Illuminate\Support\Facades\DB;
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
        //на одно изменение приходит по 2-3 события, делаем запросы в TL с ограничением: 1 запрос в 3 секунд по одной броне.
        RateLimiter::attempt(
            'SendBookingNotificationListener:' . $event->bookingId,
            1,
            function () use ($event) {
                if (! $this->isHotelIntegrated($event->bookingId)) {
                    return;
                }
                $this->travelineAdapter->sendReservationNotification();
            },
            3,
        );
    }

    private function isHotelIntegrated(int $bookingId): bool
    {
        return DB::table('booking_hotel_details')
            ->where('booking_id', $bookingId)
            ->select('traveline_hotels.hotel_id')
            ->join('traveline_hotels', 'traveline_hotels.hotel_id', '=', 'booking_hotel_details.hotel_id')
            ->exists();
    }
}
