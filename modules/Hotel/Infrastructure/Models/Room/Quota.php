<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Custom\Framework\Database\Eloquent\Model;

/**
 * Module\Hotel\Infrastructure\Models\Room\Quota
 *
 * @property int $id
 * @property int $room_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $release_days
 * @property QuotaStatusEnum $status
 * @property int $count_available
 * @property int $count_booked
 * @property int $count_reserved
 * @method static \Illuminate\Database\Eloquent\Builder|Quota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountReserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountBooked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereStatus($value)
 * @mixin \Eloquent
 */
class Quota extends Model
{
    protected $table = 'hotel_room_quota';

    protected $fillable = [
        'room_id',
        'date',
        'release_days',
        'status',
        'count_available',
        'count_booked',
        'count_reserved',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => QuotaStatusEnum::class
    ];
}
