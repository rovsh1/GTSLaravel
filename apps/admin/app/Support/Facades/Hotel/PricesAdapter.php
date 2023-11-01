<?php

namespace App\Admin\Support\Facades\Hotel;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getSeasonsPrices(int $hotelId)
 * @method static void setSeasonPrice(int $roomId, int $seasonId, int $rateId, int $guestsCount, bool $isResident, ?float $price)
 * @method static array getDatePrices(int $seasonId)
 * @method static void setDatePrice(CarbonInterface $date, int $roomId, int $seasonId, int $rateId, int $guestsCount, bool $isResident, float $price)
 */
class PricesAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\PricesAdapter::class;
    }
}
