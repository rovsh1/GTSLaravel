<?php

namespace GTS\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

use GTS\Hotel\Infrastructure\Models\Room\Name;
use GTS\Hotel\Infrastructure\Models\Room\NameTranslation;
use GTS\Shared\Infrastructure\Models\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCustomName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereDataFlags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereGuestsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereNameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room wherePriceDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereRoomsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereTypeId($value)
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

    public function scopeJoinName(){
        //@todo когда имя содержится в другой модели
    }

    public function nameTranslation()
    {
        return $this->hasOneThrough(
            NameTranslation::class,
            Name::class,
            'id',
            'translatable_id',
            'name_id',
            'id'
        );
    }

    public function frontName(): Attribute
    {
        $baseName = $this->nameTranslation?->name;
        //@todo что делать если не найдено базовое название (для выбранного языка)
        return Attribute::get(fn() => "{$baseName} ($this->custom_name)");
    }
}
