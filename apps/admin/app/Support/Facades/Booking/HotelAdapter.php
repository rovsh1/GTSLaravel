<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings()
 * @method static int getBooking(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int $hotelId, CarbonPeriod $period, ?int $orderId, ?string $note = null)
 **/
class HotelAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\HotelAdapter::class;
    }
}
