<?php

declare(strict_types=1);

namespace App\Admin\Models\Supplier;

use Illuminate\Database\Eloquent\Builder;
use Module\Supplier\Moderation\Infrastructure\Models\CarCancelConditions as Base;

class CarCancelConditions extends Base
{
    public function scopeWhereSupplierId(Builder $builder, int $supplierId): void
    {
        $builder->addSelect('supplier_car_cancel_conditions.*')
            ->join('supplier_services', 'supplier_services.id', 'supplier_car_cancel_conditions.service_id')
            ->addSelect('supplier_services.supplier_id')
            ->where('supplier_services.supplier_id', $supplierId);
    }
}
