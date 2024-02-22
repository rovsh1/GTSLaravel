<?php

namespace Module\Supplier\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\Model;

class Car extends Model
{
    protected $table = 'supplier_cars';

    protected $fillable = [
        'supplier_id',
        'car_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $query) {
            $query
                ->addSelect('supplier_cars.supplier_id')
                ->join('r_transport_cars', 'r_transport_cars.id', '=', 'supplier_cars.car_id')
                ->addSelect('r_transport_cars.*')
                ->addSelect('supplier_cars.id as id');
        });
    }

    public function scopeWhereSupplierId(Builder $builder, int $supplierId): void
    {
        $builder->where('supplier_cars.supplier_id', $supplierId);
    }
}
