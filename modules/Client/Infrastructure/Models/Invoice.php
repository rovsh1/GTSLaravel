<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Module\Booking\Order\Infrastructure\Models\Order;
use Module\Client\Domain\Invoice\ValueObject\InvoiceStatusEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $client_id
 * @property int $status
 * @property string $document
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Invoice extends Model
{
    const UPDATED_AT = null;

    protected $table = 'client_invoices';

    protected $fillable = [
        'client_id',
        'status',
        'document',
    ];

    protected $casts = [
        'client_id' => 'int',
        'status' => 'int',
    ];

    public function orderIds(): Collection
    {
        return DB::table('client_invoice_orders')
            ->where('invoice_id', $this->id)
            ->pluck('order_id');
    }

    public function scopeWhereHasOrderId(Builder $builder, int $orderId): void
    {
        $builder->whereExists(function ($query) use ($orderId) {
            $query
                ->select(DB::raw(1))
                ->from('client_invoice_orders as t')
                ->whereColumn('t.invoice_id', 'client_invoices.id')
                ->where('order_id', $orderId);
        });
    }
}
