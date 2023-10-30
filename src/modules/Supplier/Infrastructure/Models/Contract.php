<?php

namespace Module\Supplier\Infrastructure\Models;

use App\Admin\Enums\Contract\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Sdk\Module\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'supplier_contracts';

    protected $fillable = [
        'supplier_id',
        'date_start',
        'date_end',
        'status',
        'service_id',
    ];

    public function scopeWhereServiceId(Builder $builder, int $serviceId): void
    {
        $builder->whereExists(function (QueryBuilder $query) use ($serviceId) {
            $query->selectRaw(1)
                ->from('supplier_service_contracts')
                ->whereColumn('supplier_service_contracts.contract_id', '=', 'supplier_contracts.id')
                ->where('supplier_service_contracts.service_id', $serviceId);
        });
    }

    public function serviceIds(): Attribute
    {
        return Attribute::get(fn() => $this->services()->get()->pluck('id')->toArray());
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'supplier_service_contracts',
            'contract_id',
            'service_id',
        );
    }

    public function scopeWhereActive(Builder $builder): void
    {
        $builder->whereStatus(StatusEnum::ACTIVE);
    }
}
