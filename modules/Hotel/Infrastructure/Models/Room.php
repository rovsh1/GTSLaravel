<?php

namespace Module\Hotel\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Module\Hotel\Infrastructure\Models\Room\Bed;

/**
 * Module\Hotel\Infrastructure\Models\Room
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
 * @property-read string $display_name
 * @property-read \Module\Hotel\Infrastructure\Models\Room\Name|null $name
 * @property-read Collection|\Module\Hotel\Infrastructure\Models\PriceRate[] $priceRates
 * @property-read Collection|\Module\Hotel\Infrastructure\Models\Room\Bed[] $beds
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
 * @method static Builder|Room withBeds()
 * @method static Builder|Room withName()
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
        return Attribute::get(fn() => "{$this->name?->name} ($this->custom_name)");
    }

    public function scopeWithPriceRates(Builder $builder)
    {
        $builder->with('priceRates');
    }

    public function scopeWithBeds(Builder $builder)
    {
        $builder->with('beds');
    }

    public function scopeWithName(Builder $builder)
    {
        $builder->with('name');
    }

    public function priceRates()
    {
        return $this->hasManyThrough(
            PriceRate::class,
            \Module\Hotel\Infrastructure\Models\Room\PriceRate::class,
            'room_id',
            'id',
            'id',
            'rate_id'
        );
    }

    public function beds()
    {
        return $this->hasMany(Bed::class, 'room_id', 'id');
    }

    public function name()
    {
        return $this->hasOne(\Module\Hotel\Infrastructure\Models\Room\Name::class, 'id', 'name_id');
    }
}
