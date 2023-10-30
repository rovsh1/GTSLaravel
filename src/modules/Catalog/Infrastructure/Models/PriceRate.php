<?php

namespace Module\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $hotel_id
 * @property string $name
 * @property string $description
 * @property int[] $room_ids
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate whereHotelId(int $value)
 */
class PriceRate extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $table = 'hotel_price_rates';

    protected $translatable = ['name', 'description'];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_price_rates.*')
                ->joinTranslations();
        });
    }
}
