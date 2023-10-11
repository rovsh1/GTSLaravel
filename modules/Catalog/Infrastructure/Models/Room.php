<?php

namespace Module\Catalog\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Catalog\Infrastructure\Models\Room\Bed;
use Module\Catalog\Infrastructure\Models\Room\PriceRate;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

class Room extends Model
{
    use HasTranslations;

    protected $table = 'hotel_rooms';

    protected array $translatable = ['name', 'text'];

    protected $fillable = [
        'hotel_id',
        'name_id',
        'type_id',
        'rooms_number',
        'guests_count',
        'square',
        'position',
        'markup_settings',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_rooms.*')
                ->joinTranslations();
        });
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
