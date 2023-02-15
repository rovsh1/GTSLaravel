<?php

namespace GTS\Hotel\Infrastructure\Models\Room;

use GTS\Shared\Infrastructure\Models\Model;

/**
 * GTS\Hotel\Infrastructure\Models\Room\PriceRate
 *
 * @property int $rate_id
 * @property int $room_id
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate whereRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate whereRoomId($value)
 * @mixin \Eloquent
 */
class PriceRate extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'hotel_price_rate_rooms';

    protected $fillable = [
        'rate_id',
        'room_id'
    ];
}
