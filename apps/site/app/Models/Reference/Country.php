<?php

namespace App\Site\Models\Reference;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;

class Country extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['name'];

    protected $table = 'r_countries';

    protected $fillable = [
        'code',
        'name',
        'default',
        'phone_code',
        'language',
        'currency',
        'priority',
        'is_show_in_lists'
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'default' => 'bool'
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_countries.*')
                ->joinTranslations()
                ->orderBy('priority', 'desc')
                ->orderBy('name', 'asc');
        });
    }

    public function scopeWhereHasCity(Builder $query)
    {
        $query->whereExists(function (QueryBuilder $query) {
            $query->select(DB::raw(1))
                ->from('r_cities as t')
                ->whereColumn('t.country_id', 'r_countries.id');
        });
    }

    public function scopeWhereHasHotel(Builder $query)
    {
        $query->whereExists(function (QueryBuilder $query) {
            $query
                ->join('r_cities', 'r_cities.country_id', '=', 'r_countries.id')
                ->select(DB::raw(1))
                ->from('hotels as t')
                ->whereColumn('t.city_id', 'r_cities.id');
        });
    }

    public function scopeWhereHasAirport(Builder $query)
    {
        $query->whereExists(function (QueryBuilder $query) {
            $query
                ->join('r_cities', 'r_cities.country_id', '=', 'r_countries.id')
                ->select(DB::raw(1))
                ->from('r_airports as t')
                ->whereColumn('t.city_id', 'r_cities.id');
        });
    }

    public function scopeOnlyVisibleForLists(Builder $builder): void
    {
        $builder->where('is_show_in_lists', true);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}