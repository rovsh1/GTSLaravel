<?php

namespace App\Admin\Support\Facades\Hotel;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getRoomQuota(int $roomId, CarbonPeriod $period)
 * @method static void updateRoomQuota(int $roomId, CarbonInterface $date, int $count)
 * @method static void openRoomQuota(int $roomId, CarbonInterface $date)
 * @method static void closeRoomQuota(int $roomId, CarbonInterface $date)
 */
class QuotaAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\QuotaAdapter::class;
    }
}
