<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Hotel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed getHotelSettings(int $hotelId)
 * @method static void updateHotelTimeSettings(int $hotelId, string $checkInAfter, string $checkOutBefore, string|null $breakfastPeriodFrom, string|null $breakfastPeriodTo)
 */
class SettingsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\SettingsAdapter::class;
    }
}
