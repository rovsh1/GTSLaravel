<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Model\Invoice;

use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\Booking\Order\Infrastructure\Models\Order;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int id
 * @property int status
 * @property string document
 * @property DateTime created_at
 * @property DateTime updated_at
 */
class Invoice extends Model
{
    const UPDATED_AT = null;

    protected $table = 'order_invoices';

    protected $fillable = [
        'status',
        'document',
    ];

    protected $casts = [
        'status' => 'int',
//        'file' => 'int',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            'order_invoice_orders',
            'order_id',
            'invoice_id'
        );
    }
}
