<?php

namespace App\Admin\Support\Facades\Hotel;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getRoomQuota(int $roomId, CarbonPeriod $period)
 */
class QuotaAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\QuotaAdapter::class;
    }
}
