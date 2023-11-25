<?php

namespace Module\Hotel\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;

class Hotel extends Model
{
    protected $table = 'hotels';

    protected $fillable = [
        'city_id',
        'type_id',
        'currency',
        'status',
        'visibility',
        'rating',
        'name',
        'zipcode',
        'address',
        'address_lat',
        'address_lon',
        'city_distance',
        'markup_settings',
        'time_settings',
    ];

    protected $casts = [
        'city_id' => 'int',
        'type_id' => 'int',
        'currency' => CurrencyEnum::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder): void {
            $builder->addSelect('hotels.*')
                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
                ->joinTranslatable('r_cities', 'name as city_name')
                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
                ->joinTranslatable('r_countries', 'name as country_name');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('hotels.id', $id);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id', 'id');
    }
}
