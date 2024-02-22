<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Hotel;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void updateExternalNumber(int $bookingId, int $type, string|null $number)
 * @method static void update(int $bookingId, CarbonPeriod $period, string|null $note)
 **/
class DetailsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Hotel\DetailsAdapter::class;
    }
}
