<?php

namespace App\Admin\Support\Facades\Hotel;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getSeasonsPrices(int $hotelId)
 * @method static mixed setSeasonPrice(int $roomId, int $seasonId, int $rateId, int $guestsNumber, bool $isResident, float $price, int $currencyId)
 * @method static array getDatePrices(int $seasonId)
 * @method static mixed setDatePrice(CarbonInterface $date, int $roomId, int $seasonId, int $rateId, int $guestsNumber, bool $isResident, float $price, int $currencyId)
 */
class PricesAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\PricesAdapter::class;
    }
}
