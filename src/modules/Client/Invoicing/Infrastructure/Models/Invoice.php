<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceStatusEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $client_id
 * @property InvoiceStatusEnum $status
 * @property string $document
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'client_invoices';

    protected $attributes = [
        'status' => InvoiceStatusEnum::NOT_PAID,
    ];

    protected $fillable = [
        'client_id',
        'status',
        'document',
    ];

    protected $casts = [
        'client_id' => 'int',
        'status' => InvoiceStatusEnum::class,
    ];

    public function orderIds(): Collection
    {
        return DB::table('orders')
            ->where('invoice_id', $this->id)
            ->pluck('id');
    }

    public function scopeWhereHasOrderId(Builder $builder, int $orderId): void
    {
        $builder->whereExists(function ($query) use ($orderId) {
            $query
                ->selectRaw(1)
                ->from('orders as t')
                ->whereColumn('t.invoice_id', 'client_invoices.id')
                ->where('t.id', $orderId);
        });
    }
}
