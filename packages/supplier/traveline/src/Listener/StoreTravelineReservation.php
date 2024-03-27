<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Listener;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Pkg\Supplier\Traveline\Models\TravelineReservation;
use Sdk\Booking\Enum\QuotaProcessingMethodEnum;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class StoreTravelineReservation
{
    public function handle(BookingEventInterface $event): void
    {
        //на одно изменение приходит по 2-3 события, делаем запросы в TL с ограничением: 1 запрос в 3 секунд по одной броне.
        RateLimiter::attempt(
            'RegisterReservationChanges:' . $event->bookingId,
            1,
            function () use ($event) {
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
            3,
        );
    }

    private function createTravelineReservation(int $bookingId): void
    {
        $hotelId = DB::table('booking_hotel_details')
            ->where('booking_id', $bookingId)
            ->whereIn('quota_processing_method', [QuotaProcessingMethodEnum::QUOTA, QuotaProcessingMethodEnum::SITE])
            ->select('traveline_hotels.hotel_id')
            ->join('traveline_hotels', 'traveline_hotels.hotel_id', '=', 'booking_hotel_details.hotel_id')
            ->first()
            ?->hotel_id;
        if ($hotelId === null) {
            return;
        }

        TravelineReservation::updateOrCreate(
            ['reservation_id' => $bookingId],
            ['hotel_id' => $hotelId, 'accepted_at' => null, 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
