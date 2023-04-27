<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Custom\Framework\Database\Eloquent\Model;

/**
 * Module\HotelOld\Infrastructure\Models\Room\Bed
 *
 * @property int $room_id
 * @property int $type_id
 * @property int $beds_number
 * @property string $beds_size
 * @method static \Illuminate\Database\Eloquent\Builder|Bed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereBedsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereBedsSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereTypeId($value)
 * @mixin \Eloquent
 */
class Bed extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'hotel_room_beds';

    protected $fillable = [
        'room_id',
        'type_id',
        'beds_number',
        'beds_size',
    ];
}
