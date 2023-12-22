<?php

namespace App\Hotel\Support\Facades;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getQuotas(int $hotelId, CarbonPeriod $period, int|null $roomId = null)
 * @method static array getAvailableQuotas(int $hotelId, CarbonPeriod $period, int|null $roomId = null)
 * @method static array getSoldQuotas(int $hotelId, CarbonPeriod $period, int|null $roomId = null)
 * @method static array getStoppedQuotas(int $hotelId, CarbonPeriod $period, int|null $roomId = null)
 * @method static void updateRoomQuota(int $roomId, CarbonInterface $date, int|null $count, int|null $releaseDays=null)
 * @method static void openRoomQuota(int $roomId, CarbonInterface $date)
 * @method static void closeRoomQuota(int $roomId, CarbonInterface $date)
 * @method static void resetRoomQuota(int $roomId, CarbonInterface $date)
 */
class QuotaAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Hotel\Support\Adapters\QuotaAdapter::class;
    }
}