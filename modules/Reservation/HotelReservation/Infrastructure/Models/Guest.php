<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\Model;

/**
 * Module\Reservation\HotelReservation\Infrastructure\Models\Guest
 *
 * @property int $id
 * @property int $room_id
 * @property int $nationality_id
 * @property string $fullname
 * @property int $gender
 * @method static \Illuminate\Database\Eloquent\Builder|Guest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereNationalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereRoomId($value)
 * @mixin \Eloquent
 */
class Guest extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    protected $table = 'reservation_guests';

    protected $fillable = [
        'room_id',
        'nationality_id',
        'fullname',
        'gender',
    ];

}
