<?php

namespace App\Admin\Models\Reference;

use App\Admin\Support\Models\HasCoordinates;
use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    use HasQuicksearch;
    use HasTranslations;
    use HasCoordinates;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected array $translatable = ['name'];

    protected $table = 'r_cities';

    protected $fillable = [
        'name',
        'country_id',
        //'text'
        'center_lat',
        'center_lon',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_cities.*')
                ->addSelect(['r_cities.id', 'r_cities.country_id'])
                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
                ->joinTranslatable('r_countries', 'name as country_name')
                ->joinTranslations()
                //TODO add priority column
                //->orderBy('priority', 'desc')
                ->orderBy('name', 'asc');
        });
    }

    public function scopeWhereHasHotel($query)
    {
        $query->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('hotels as t')
                ->whereColumn('t.city_id', 'r_cities.id');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    protected function getLatitudeField(): string
    {
        return 'center_lat';
    }

    protected function getLongitudeField(): string
    {
        return 'center_lon';
    }
}
