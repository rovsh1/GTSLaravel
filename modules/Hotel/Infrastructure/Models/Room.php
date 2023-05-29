<?php

namespace Module\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Module\Hotel\Infrastructure\Models\Room\Bed;
use Module\Hotel\Infrastructure\Models\Room\PriceRate;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

class Room extends Model
{
    use HasTranslations;

    protected $table = 'hotel_rooms';

    protected array $translatable = ['name', 'text'];

    protected $appends = ['display_name'];

    protected $fillable = [
        'hotel_id',
        'name_id',
        'type_id',
        'custom_name',
        'rooms_number',
        'guests_number',
        'square',
        'position',
        'markup_settings',
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
