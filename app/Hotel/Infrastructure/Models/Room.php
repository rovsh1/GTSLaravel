<?php

namespace GTS\Hotel\Infrastructure\Models;

use GTS\Shared\Custom\Database\Eloquent\HasTranslatableName;
use GTS\Shared\Infrastructure\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * GTS\Hotel\Infrastructure\Models\Room
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $type_id
 * @property int $name_id
 * @property string|null $custom_name
 * @property int $rooms_number
 * @property int $guests_number
 * @property int|null $size
 * @property int|null $price_discount
 * @property int $data_flags
 * @property int $index
 * @property string $name
 * @property-read string $display_name
 * @property-read \GTS\Hotel\Infrastructure\Models\PriceRate|null $priceRate
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static Builder|Room query()
 * @method static Builder|Room whereCustomName($value)
 * @method static Builder|Room whereDataFlags($value)
 * @method static Builder|Room whereGuestsNumber($value)
 * @method static Builder|Room whereHotelId($value)
 * @method static Builder|Room whereId($value)
 * @method static Builder|Room whereIndex($value)
 * @method static Builder|Room whereNameId($value)
 * @method static Builder|Room wherePriceDiscount($value)
 * @method static Builder|Room whereRoomsNumber($value)
 * @method static Builder|Room whereSize($value)
 * @method static Builder|Room whereTypeId($value)
 * @method static Builder|Room withPriceRate()
 * @mixin \Eloquent
 */
class Room extends Model
{
    use HasTranslatableName;

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'hotel_rooms';

    protected $fillable = [
        'hotel_id',
        'type_id',
        'name_id',
        'custom_name',
        'rooms_number',
        'guests_number',
        'size',
        'price_discount',
        'data_flags',
        'index',
    ];

    public function displayName(): Attribute
    {
        return Attribute::get(fn() => "{$this->name} ($this->custom_name)");
    }

    public function scopeWithPriceRate(Builder $builder)
    {
        $localKey = "{$this->getTable()}.{$this->getKeyName()}";
        $builder->join('hotel_price_rate_rooms', $localKey, '=', 'hotel_price_rate_rooms.room_id')
            ->select('hotel_price_rate_rooms.rate_id')
            ->with('priceRate');
    }

    public function priceRate()
    {
        return $this->hasOne(
            PriceRate::class,
            'id',
            'rate_id'
        );
    }
}
