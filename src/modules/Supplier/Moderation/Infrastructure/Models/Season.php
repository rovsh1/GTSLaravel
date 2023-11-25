<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\Model;

class Season extends Model
{
    protected $table = 'supplier_seasons';

    protected $fillable = [];

    public function scopeWhereServiceId(Builder $builder, int $serviceId): void
    {
        $builder->whereExists(function (Query $query) use ($serviceId) {
            $query->selectRaw(1)
                ->from('supplier_services')
                ->whereColumn('supplier_services.supplier_id', 'supplier_seasons.supplier_id')
                ->where('supplier_services.id', $serviceId);
        });
    }

    public function scopeWhereIncludeDate(Builder $builder, \DateTimeInterface $date): void
    {
        $builder->whereDate('date_start', '<=', $date)
            ->whereDate('date_end', '>=', $date);
    }
}
