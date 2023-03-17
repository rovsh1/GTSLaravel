<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Airport extends Model
{
    public $timestamps = false;

    protected $table = 'r_airports';

    protected $fillable = [
        'city_id',
        'name',
        'code',
    ];

    protected $casts = [
        'city_id' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_airports.*')
                ->join('r_cities', 'r_cities.id', '=', 'r_airports.city_id')
                //->joinTranslations($builder->getModel()->translatable)
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
