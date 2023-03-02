<?php

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy;

use Custom\Framework\Database\Eloquent\Model;

/**
 * Module\Integration\Traveline\Infrastructure\Models\Room
 *
 * @property int $id
 * @property int $reservation_id
 * @property int $room_id
 * @property int|null $checkin_id
 * @property int|null $checkout_id
 * @property int $rate_id
 * @property int $status_id
 * @property int|null $discount
 * @property int $price_type
 * @property int $rooms_number
 * @property int $guests_number
 * @property string|null $note
 * @property string|null $price_gross
 * @property string|null $price_net
 * @property string|null $price_net_hotel
 * @property string|null $changed_gross
 * @property string|null $changed_net
 * @property string|null $details
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Module\Integration\Traveline\Infrastructure\Models\Guest> $guests
 * @method static \Illuminate\Database\Eloquent\Builder|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereChangedGross($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereChangedNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCheckinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCheckoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereGuestsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room wherePriceGross($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room wherePriceNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room wherePriceNetHotel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room wherePriceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereRoomsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereStatusId($value)
 * @property-read \Module\Integration\Traveline\Infrastructure\Models\Room\CheckInOutConditions|null $checkInCondition
 * @property-read \Module\Integration\Traveline\Infrastructure\Models\Room\CheckInOutConditions|null $checkOutCondition
 * @mixin \Eloquent
 */
class Room extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    protected $table = 'reservation_rooms';

    protected $fillable = [
        'reservation_id',
        'room_id',
        'checkin_id',
        'checkout_id',
        'rate_id',
        'status_id',
        'discount',
        'price_type',
        'rooms_number',
        'guests_number',
        'note',
        'price_gross',
        'price_net',
        'price_net_hotel',
        'changed_gross',
        'changed_net',
        'details',
    ];

    public function guests()
    {
        return $this->hasMany(
            Guest::class,
            'room_id',
            'id',
        );
    }

    public function checkInCondition()
    {
        return $this->belongsTo(
            Room\CheckInOutConditions::class,
            'checkin_id',
            'id',
        );
    }

    public function checkOutCondition()
    {
        return $this->belongsTo(
            Room\CheckInOutConditions::class,
            'checkout_id',
            'id'
        );
    }

    public function dailyPrices()
    {
        return $this->hasMany(
            Room\DayPrice::class,
            'reservation_room_id',
            'id'
        );
    }

}
