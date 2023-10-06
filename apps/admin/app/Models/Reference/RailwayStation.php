<?php

namespace App\Admin\Models\Reference;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class RailwayStation extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'name%'];

    public $timestamps = false;

    protected $table = 'r_railway_stations';

    protected $fillable = [
        'city_id',
        'name',
    ];

    protected $casts = [
        'city_id' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_railway_stations.*')
                ->join('r_cities', 'r_cities.id', '=', 'r_railway_stations.city_id')
                //->joinTranslations($builder->getModel()->translatable)
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
