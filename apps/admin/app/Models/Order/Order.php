<?php

declare(strict_types=1);

namespace App\Admin\Models\Order;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Order extends \Module\Booking\Shared\Infrastructure\Order\Models\Order
{
    use HasQuicksearch;

    protected array $quicksearch = ['id'];

    public function scopeApplyCriteria(Builder $query, array $criteria): void
    {
        if (isset($criteria['quicksearch'])) {
            $query->quicksearch($criteria['quicksearch']);
            unset($criteria['quicksearch']);
        }

        foreach ($criteria as $k => $v) {
            $scopeName = \Str::camel($k);
            $scopeMethod = 'where' . ucfirst($scopeName);
            $hasScope = $this->hasNamedScope($scopeMethod);
            if ($hasScope) {
                $query->$scopeMethod($v);
                continue;
            }
            $query->where($k, $v);
        }
    }
}
