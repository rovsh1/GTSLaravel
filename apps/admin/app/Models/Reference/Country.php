<?php

namespace App\Admin\Models\Reference;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

class Country extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name'];

    protected $table = 'r_countries';

    protected $fillable = [
        'code',
        'name',
        'default',
        'phone_code',
        'language',
        'currency_id'
    ];

    protected $casts = [
        'default' => 'bool'
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_countries.*')
                ->joinTranslations()
                //TODO add priority column
                //->orderBy('priority', 'desc')
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

    public function __toString()
    {
        return (string)$this->name;
    }
}
