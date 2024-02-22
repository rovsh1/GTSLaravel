<?php

namespace Module\Hotel\Pricing\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;

class Hotel extends Model
{
    protected $table = 'hotels';

    protected $casts = [
        'city_id' => 'int',
        'markup_settings' => 'array',
        'currency' => CurrencyEnum::class,
    ];

    public static function findByRoomId(int $roomId): static
    {
        return static::whereRaw('EXISTS(SELECT 1 FROM hotel_rooms WHERE id=? AND hotel_id=hotels.id)', [$roomId])
            ->first();
    }
}
