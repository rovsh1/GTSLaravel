<?php

namespace GTS\Hotel\Infrastructure\Models;

use GTS\Shared\Infrastructure\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;

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
 * @property-read Collection|\GTS\Hotel\Infrastructure\Models\PriceRate[] $priceRates
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
 * @method static Builder|Room withPriceRates()
 * @mixin \Eloquent
 */
class Room extends Model
{
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

    public function scopeWithPriceRates(Builder $builder)
    {
        $builder->with('priceRates');
    }

    public function scopeTest($q){
        (new Scope($q,'hotel_room_translations'))//r_enum_translations
            ->join(Room\Name::class,['name'])
            ->join('r_enum_translations',['name']);

        //scope code
        $this->getTable().'_tranlsations';
    }

    public function priceRates()
    {
        return $this->hasManyThrough(
            PriceRate::class,
            Room\PriceRate::class,
            'room_id',
            'id',
            'id',
            'rate_id'
        );
    }
}
