<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Listener;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Pkg\Supplier\Traveline\Models\TravelineReservation;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class StoreTravelineReservation
{
    public function handle(BookingEventInterface $event): void
    {
        //на одно изменение приходит по 2-3 события, делаем запросы в TL с ограничением: 1 запрос в 5 секунд по одной броне.
        RateLimiter::attempt(
            'RegisterReservationChanges:' . $event->bookingId,
            1,
            function () use ($event) {
                \Log::debug('[Traveline] RegisterReservationChanges', ['event' => $event]);

                $travelineReservation = TravelineReservation::whereReservationId($event->bookingId)->first();
                if ($travelineReservation === null) {
                    $this->createTravelineReservation($event->bookingId);

                    return;
                }

                if ($travelineReservation->accepted_at === null) {
                    return;
                }

                TravelineReservation::whereReservationId($event->bookingId)->update([
                    'accepted_at' => null,
                    'updated_at' => now()
                ]);
            },
            5,
        );
    }

    private function createTravelineReservation(int $bookingId): void
    {
        $hotelId = DB::table('booking_hotel_details')
            ->where('booking_id', $bookingId)
            ->select('hotel_id')
            ->first()
            ?->hotel_id;
        if ($hotelId === null) {
            \Log::warning('[Traveline] Booking without hotel id', ['booking_id' => $bookingId]);

            return;
        }

        TravelineReservation::create([
            'hotel_id' => $hotelId,
            'reservation_id' => $bookingId,
            'accepted_at' => null,
            'updated_at' => now()
        ]);
    }
}
