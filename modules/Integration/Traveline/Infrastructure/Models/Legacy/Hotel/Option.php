<?php

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy\Hotel;

use Custom\Framework\Database\Eloquent\Model;

/**
 * Module\Integration\Traveline\Infrastructure\Models\HotelOption
 *
 * @property int $hotel_id
 * @property int $option
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|Option newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option query()
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereValue($value)
 * @mixin \Eloquent
 */
class Option extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    protected $table = 'hotel_options';

    protected $fillable = [
        'hotel_id',
        'option',
        'value'
    ];

    protected $casts = [
        'option' => OptionTypeEnum::class
    ];
}
