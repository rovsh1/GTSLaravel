<?php

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy\Room;

use Custom\Framework\Database\Eloquent\Model;

class DayPrice extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    protected $table = 'reservation_room_prices';

    protected $fillable = [
        'reservation_room_id',
        'season_id',
        'type',
        'date',
        'hotel_discount',
        'brutto',
        'netto',
        'netto_with_discount',
        'discount',
        'brutto_without_discount',
        'check_in',
        'check_in_netto',
        'check_in_netto_hotel',
        'check_out',
        'check_out_netto',
        'check_out_netto_hotel',
        'netto_calculate_hotel',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'type' => DayPriceTypeEnum::class
    ];

}
