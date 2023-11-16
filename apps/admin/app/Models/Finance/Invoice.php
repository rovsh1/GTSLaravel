<?php

declare(strict_types=1);

namespace App\Admin\Models\Finance;

use App\Admin\Models\Order\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Client\Invoicing\Application\UseCase\GetDocumentFile;

class Invoice extends \Module\Client\Invoicing\Infrastructure\Models\Invoice
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

    public function file(): Attribute
    {
        return Attribute::get(
            fn() => $this->document
                ? app(GetDocumentFile::class)->execute($this->document)
                : null
        );
    }

    public function setOrderIdsAttribute(array $orderIds): void
    {
        $this->savingOrderIds = $orderIds;
    }

    public function getOrderIdsAttribute(): array
    {
        return $this->orders()->pluck('id')->toArray();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function __toString()
    {
        return "Инвойс №{$this->id}";
    }
}
