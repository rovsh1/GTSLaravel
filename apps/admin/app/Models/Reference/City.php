<?php

namespace App\Admin\Models\Reference;

use App\Admin\Support\Models\HasCoordinates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

/**
 * App\Admin\Models\Reference\City
 *
 * @property int $id
 * @property int $country_id
 * @property float|null $center_lat
 * @property float|null $center_lon
 * @property-write mixed $translatable
 * @method static Builder|City joinTranslations($columns = null)
 * @method static Builder|City newModelQuery()
 * @method static Builder|City newQuery()
 * @method static Builder|City query()
 * @method static Builder|City quicksearch($term)
 * @method static Builder|City whereCenterLat($value)
 * @method static Builder|City whereCenterLon($value)
 * @method static Builder|City whereCountryId($value)
 * @method static Builder|City whereHasHotel()
 * @method static Builder|City whereHasAirport()
 * @method static Builder|City whereId($value)
 * @mixin \Eloquent
 */
class City extends Model
{
    use HasQuicksearch;
    use HasTranslations;
    use HasCoordinates;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'r_cities.name%'];

    protected array $translatable = ['name'];

    protected $table = 'r_cities';

    protected $fillable = [
        'name',
        'country_id',
        //'text'
        'center_lat',
        'center_lon',
        'priority'
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
                ->orderBy('priority', 'desc')
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

    public function scopeWhereHasAirport($query)
    {
        $query->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('r_airports as t')
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
