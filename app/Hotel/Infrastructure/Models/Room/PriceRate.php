<?php

namespace GTS\Hotel\Infrastructure\Models\Room;

use GTS\Shared\Infrastructure\Models\Model;

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
