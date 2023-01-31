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
 * @property string $custom_name
 * @property int $rooms_number
 * @property int $guests_number
 * @property int $size
 * @property int $price_discount
 * @property int $data_flags
 * @property int $index
 * @property-read NameTranslation|null $nameTranslation
 * @property-read string $front_name
 * @mixin \Illuminate\Database\Eloquent\Model
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
