<?php

namespace Module\Hotel\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Module\Hotel\Infrastructure\Models\Room\Bed;
use Module\Hotel\Infrastructure\Models\Room\PriceRate;

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
 * @property-read string|null $name
 * @property-read Collection|\Module\Hotel\Infrastructure\Models\Room\PriceRate[] $priceRates
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
 * @mixin \Eloquent
 */
class Room extends Model
{
    use HasTranslations;

    protected $table = 'hotel_rooms';

    protected array $translatable = ['name', 'text'];

    protected $appends = ['display_name'];

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

    public function scopeWithBeds(Builder $builder)
    {
        $builder->with('beds');
    }

    public function priceRates()
    {
        return $this->belongsToMany(
            PriceRate::class,
            'hotel_price_rate_rooms',
            'room_id',
            'rate_id',
            'id',
            'id'
        );
    }

    public function beds()
    {
        return $this->hasMany(Bed::class, 'room_id', 'id');
    }
}
