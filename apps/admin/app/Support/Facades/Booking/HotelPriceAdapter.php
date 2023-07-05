<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setBoPrice(int $bookingId, float $price)
 * @method static void setHoPrice(int $bookingId, float $price)
 * @method static void setCalculatedBoPrice(int $bookingId)
 * @method static void setCalculatedHoPrice(int $bookingId)
 * @method static void setCalculatedPrices(int $bookingId)
 *
 * @method static void setBoRoomPrice(int $bookingId, int $roomBookingId, float $price)
 * @method static void setHoRoomPrice(int $bookingId, int $roomBookingId, float $price)
 * @method static void setCalculatedBoRoomPrice(int $bookingId, int $roomBookingId)
 * @method static void setCalculatedHoRoomPrice(int $bookingId, int $roomBookingId)
 * @method static void setCalculatedRoomPrices(int $bookingId, int $roomBookingId)
 */
class HotelPriceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\HotelPriceAdapter::class;
    }
}
