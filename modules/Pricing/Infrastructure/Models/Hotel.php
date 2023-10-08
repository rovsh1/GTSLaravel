<?php

namespace Module\Pricing\Infrastructure\Models;

use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Database\Eloquent\Model;

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
