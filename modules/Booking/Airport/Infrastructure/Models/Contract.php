<?php

namespace Module\Booking\Airport\Infrastructure\Models;

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

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->whereServiceType(ContractServiceTypeEnum::AIRPORT);
        });
    }

    public function scopeWhereActive(Builder $builder): void
    {
        $builder->whereStatus(StatusEnum::ACTIVE);
    }
}
