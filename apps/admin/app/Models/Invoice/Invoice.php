<?php

declare(strict_types=1);

namespace App\Admin\Models\Invoice;

use App\Admin\Models\Order\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends \Module\Client\Infrastructure\Models\Invoice
{
    private array $savingOrderIds;

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('client_invoices.*')
                ->join('clients', 'clients.id', 'client_invoices.client_id')
                ->addSelect('clients.name as client_name');
        });

        static::saved(function (self $model) {
            if (isset($model->savingOrderIds)) {
                $model->orders()->sync($model->savingOrderIds);
                unset($model->savingOrderIds);
            }
        });
    }

    public function getFillable()
    {
        return [
            ...$this->fillable,
            'order_ids',
        ];
    }

    public function setOrderIdsAttribute(array $orderIds): void
    {
        $this->savingOrderIds = $orderIds;
    }

    public function getOrderIdsAttribute(): array
    {
        return $this->orders()->pluck('id')->toArray();
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            'client_invoice_orders',
            'invoice_id',
            'order_id'
        );
    }

    public function __toString()
    {
        return "Инвойс №{$this->id}";
    }
}
