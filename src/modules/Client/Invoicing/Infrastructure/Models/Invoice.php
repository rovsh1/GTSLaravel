<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $client_id
 * @property int $order_id
 * @property string $document
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'client_invoices';

    protected $fillable = [
        'client_id',
        'order_id',
        'document',
    ];

    protected $casts = [
        'client_id' => 'int',
    ];

    public function scopeWhereOrderId(Builder $builder, int $orderId): void
    {
        $builder->where('order_id', $orderId);
    }
}
