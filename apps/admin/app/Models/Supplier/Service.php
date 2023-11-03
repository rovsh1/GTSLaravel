<?php

namespace App\Admin\Models\Supplier;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Service extends \Module\Supplier\Infrastructure\Models\Service
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'supplier_services.%title%'];

    protected $casts = [
        'data' => 'array',
        'supplier_id' => 'int',
        'type' => ServiceTypeEnum::class,
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->orderBy('name')
                ->addSelect('supplier_services.*')
                ->join('suppliers', 'suppliers.id', '=', 'supplier_services.supplier_id')
                ->addSelect('suppliers.name as provider_name');
        });
    }

    public function scopeWhereCity(Builder $builder, int $cityId): void
    {
        $builder->whereExists(function (QueryBuilder $query) use ($cityId) {
            $query->select(DB::raw(1))
                ->from('supplier_cities as t')
                ->whereColumn('t.supplier_id', 'supplier_services.supplier_id')
                ->where('city_id', $cityId);
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
