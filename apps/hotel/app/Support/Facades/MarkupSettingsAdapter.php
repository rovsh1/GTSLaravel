<?php

declare(strict_types=1);

namespace App\Hotel\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getHotelMarkupSettings(int $hotelId)
 * @method static mixed updateMarkupSettings(int $hotelId, string $key, mixed $value)
 * @method static mixed addMarkupSettingsCondition(int $hotelId, string $key, mixed $value)
 * @method static mixed deleteMarkupSettingsCondition(int $hotelId, string $key, int $index)
 */
class MarkupSettingsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Hotel\Support\Adapters\MarkupSettingsAdapter::class;
    }
}
