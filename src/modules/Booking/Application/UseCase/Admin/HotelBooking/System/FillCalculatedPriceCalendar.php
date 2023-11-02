<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\HotelBooking\System;

use Module\Catalog\Infrastructure\Models\SeasonPrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FillCalculatedPriceCalendar implements UseCaseInterface
{
    public function execute(int $hotelId): void
    {
        $seasonPrices = SeasonPrice::whereHotelId($hotelId)->with(['season'])->get();

        $pricesData = [];
        foreach ($seasonPrices as $seasonPrice) {
            foreach ($seasonPrice->season->period->toArray() as $date) {
                $pricesData[] = [
                    'date' => $date,
                    'season_id' => $seasonPrice->season->id,
                    'group_id' => $seasonPrice->group_id,
                    'price' => $seasonPrice->price,
                    'room_id' => $seasonPrice->room_id,
                ];
            }
        }

        \DB::table('hotel_season_price_calendar')->upsert(
            $pricesData,
            ['date', 'season_id', 'group_id', 'room_id'],
            ['date', 'season_id', 'group_id', 'room_id', 'price']
        );
    }
}
