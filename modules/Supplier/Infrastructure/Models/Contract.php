<?php

namespace Module\Supplier\Infrastructure\Models;

use App\Admin\Enums\Contract\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'supplier_contracts';

    protected $fillable = [
        'supplier_id',
        'date_start',
        'date_end',
        'status',
        'service_type',
        'service_id',
    ];

    protected $casts = [
        'service_type' => ContractServiceTypeEnum::class
    ];

    public function scopeWhereActive(Builder $builder): void
    {
        $builder->whereStatus(StatusEnum::ACTIVE);
    }
}
