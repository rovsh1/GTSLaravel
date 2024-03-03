<?php

namespace App\Admin\Support\Facades\Hotel;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getQuotasAvailability(CarbonPeriod $period, int[] $cityIds = [], int[] $hotelIds = [], int[] $roomIds = [])
 */
class QuotaAvailabilityAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\QuotaAvailabilityAdapter::class;
    }
}
