<?php

declare(strict_types=1);

namespace App\Hotel\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void updateExternalNumber(int $bookingId, int $type, string|null $number)
 **/
class DetailsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Hotel\Support\Adapters\Booking\DetailsAdapter::class;
    }
}
