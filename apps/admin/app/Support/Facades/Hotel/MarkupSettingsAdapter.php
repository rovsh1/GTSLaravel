<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Hotel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getHotelMarkupSettings(int $hotelId)
 * @method static mixed updateMarkupSettings(int $hotelId, string $key, mixed $value)
 * @method static mixed addMarkupSettingsCondition(int $hotelId, string $key, mixed $value)
 * @method static mixed deleteMarkupSettingsCondition(int $hotelId, string $key, int $index)
 * @method static object getRoomMarkupSettings(int $hotelId, int $roomId)
 * @method static mixed updateRoomMarkupSettings(int $roomId, string $key, int $value)
 */
class MarkupSettingsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\MarkupSettingsAdapter::class;
    }
}
