<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setBoPrice(int $bookingId, float $price)
 * @method static void setHoPrice(int $bookingId, float $price)
 * @method static void setCalculatedBoPrice(int $bookingId)
 * @method static void setCalculatedHoPrice(int $bookingId)
 * @method static void setHoPenalty(int $bookingId, float|null $penalty)
 * @method static void setBoPenalty(int $bookingId, float|null $penalty)
 *
// * @method static void setBoRoomPrice(int $bookingId, int $roomBookingId, float $price)
// * @method static void setHoRoomPrice(int $bookingId, int $roomBookingId, float $price)
// * @method static void setCalculatedBoRoomPrice(int $bookingId, int $roomBookingId)
// * @method static void setCalculatedHoRoomPrice(int $bookingId, int $roomBookingId)
// * @method static void setCalculatedRoomPrices(int $bookingId, int $roomBookingId)
 * @method static void updateRoomPrice(int $bookingId, int $roomBookingId, float|null $boPrice, float|null $hoPrice)
 */
class HotelPriceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\HotelPriceAdapter::class;
    }
}
