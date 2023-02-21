<?php

namespace Module\Hotel\Infrastructure\Models;

use Module\Shared\Infrastructure\Models\Model;

/**
 * GTS\Hotel\Infrastructure\Models\Contract
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $number
 * @property int $status
 * @property \Custom\Framework\Support\DateTime $date_from
 * @property \Custom\Framework\Support\DateTime $date_to
 * @property \Custom\Framework\Support\DateTime $created
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
    public const CREATED_AT = 'created';
    public const UPDATED_AT = null;

    protected $table = 'hotel_contracts';

    protected $fillable = [
        'hotel_id',
        'number',
        'status',
        'date_from',
        'date_to',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];
}
