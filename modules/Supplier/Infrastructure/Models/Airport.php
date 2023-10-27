<?php

namespace Module\Supplier\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\Model;

class Airport extends Model
{
    public $timestamps = false;

    protected $table = 'r_airports';

    protected $fillable = [
    ];

    protected $casts = [
        'city_id' => 'int',
    ];

    public static function booted(): void
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_airports.*')
                ->join('r_cities', 'r_cities.id', '=', 'r_airports.city_id')
                //->joinTranslations($builder->getModel()->translatable)
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }
}
