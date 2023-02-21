<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Module\Shared\Infrastructure\Models\Model;

/**
 * GTS\Hotel\Infrastructure\Models\Room\Price
 *
 * @property int $room_id
 * @property int $season_id
 * @property int $rate_id
 * @property int $guests_number
 * @property int $type
 * @property string $price
 * @property \Custom\Framework\Support\DateTime $date
 * @method static \Illuminate\Database\Eloquent\Builder|Price newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereGuestsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereType($value)
 * @mixin \Eloquent
 */
class Price extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'hotel_room_price_days';

    protected $fillable = [
        'rate_id',
        'season_id',
        'room_id',
        'guests_number',
        'type',
        'price',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'type' => PriceTypeEnum::class
    ];
}
