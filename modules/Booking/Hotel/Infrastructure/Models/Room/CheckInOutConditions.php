<?php

namespace Module\Booking\Hotel\Infrastructure\Models\Room;

use Custom\Framework\Database\Eloquent\Model;

/**
 * Module\Booking\Hotel\Infrastructure\Models\Room\CheckInOutConditions
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $type
 * @property int $price_markup
 * @property string $start
 * @property string $end
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions query()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions wherePriceMarkup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckInOutConditions whereType($value)
 * @mixin \Eloquent
 */
class CheckInOutConditions extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    protected $table = 'hotel_residence_conditions';

    protected $fillable = [
        'hotel_id',
        'type',
        'price_markup',
        'start',
        'end',
    ];
}
