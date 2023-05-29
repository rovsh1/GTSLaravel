<?php

namespace Module\HotelOld\Infrastructure\Models\Room;

use Sdk\Module\Database\Eloquent\Model;

/**
 * Module\HotelOld\Infrastructure\Models\Room\Quota
 *
 * @property int $room_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $count_available
 * @property int $period
 * @property int $count_booked
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|Quota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountBooked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereType($value)
 * @mixin \Eloquent
 */
class Quota extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'hotel_room_quotes';

    protected $fillable = [
        'room_id',
        'date',
        'count_available',
        'period',
        'count_booked',
        'type',
    ];

    protected $casts = [
        'date' => 'date',
        'type' => QuotaTypeEnum::class
    ];
}
