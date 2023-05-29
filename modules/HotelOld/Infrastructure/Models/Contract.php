<?php

namespace Module\HotelOld\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

/**
 * Module\HotelOld\Infrastructure\Models\Contract
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $number
 * @property int $status
 * @property \Sdk\Module\Support\DateTime $date_from
 * @property \Sdk\Module\Support\DateTime $date_to
 * @property \Sdk\Module\Support\DateTime $created
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereStatus($value)
 * @mixin \Eloquent
 */
class Contract extends Model
{
    protected $table = 'hotel_contracts';

    protected $fillable = [
        'hotel_id',
        'number',
        'status',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];
}
