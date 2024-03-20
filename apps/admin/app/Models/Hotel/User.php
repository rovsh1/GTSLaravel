<?php

namespace App\Admin\Models\Hotel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class User extends Model
{
    use HasQuicksearch, SoftDeletes;

    public $timestamps = false;

    protected array $quicksearch = ['id', '%presentation%', '%login%', '%email%'];

    protected $table = 'hotel_administrators';

    protected $attributes = [
        'status' => 1,
    ];

    protected $fillable = [
        'hotel_id',
        'presentation',
        'login',
        'password',
        'email',
        'phone',
        'status',
    ];

    protected $casts = [
        'hotel_id' => 'int',
        'status' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_administrators.*')
                ->addSelect('hotels.name as hotel_name')
                ->join('hotels', 'hotels.id', '=', 'hotel_administrators.hotel_id')
                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function whereCityId(Builder $builder, int $cityId): void
    {
        $builder->where('hotels.city_id', $cityId);
    }

    public function whereCountryId(Builder $builder, int $countryId): void
    {
        $builder->where('r_cities.country_id', $countryId);
    }

    public function setAttribute($key, $value)
    {
        if ($key === 'password') {
            $value = $value ? Hash::make($value) : null;
        }

        return parent::setAttribute($key, $value);
    }

    public function __toString()
    {
        return (string)$this->presentation;
    }
}
