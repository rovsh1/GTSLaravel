<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Hotel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getHotelMarkupSettings(int $hotelId)
 * @method static mixed updateClientMarkups(int $hotelId, int|null $individual, int|null $OTA, int|null $TA, int|null $TO)
 */
class MarkupSettingsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\MarkupSettingsAdapter::class;
    }
}
