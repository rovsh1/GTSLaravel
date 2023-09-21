<?php

namespace App\Admin\Models\ServiceProvider;

use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\TransportCar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'supplier_airports';

    public $timestamps = false;

    protected $fillable = [
        'provider_id',
        'airport_id',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('supplier_airports.*')
                ->join('r_airports', 'r_airports.id', '=', 'supplier_airports.airport_id')
                ->addSelect('r_airports.name as airport_name')
                ->join('r_cities', 'r_cities.id', '=', 'r_airports.city_id')
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function airport(): BelongsTo
    {
        return $this->belongsTo(\App\Admin\Models\Reference\Airport::class);
    }

    public function __toString()
    {
        return (string)$this->airport_name;
    }
}
