<?php

namespace Module\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\HotelOld\Infrastructure\Models\Room;
use Sdk\Module\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hotels';

    protected $fillable = [
        'city_id',
        'type_id',
        'currency_id',
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
