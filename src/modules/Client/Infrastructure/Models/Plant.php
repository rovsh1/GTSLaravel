<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Models;

use DateTime;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $payment_id
 * @property int $invoice_id
 * @property int $client_id
 * @property DateTime $created_at
 */
class Plant extends Model
{
    const UPDATED_AT = null;

    protected $table = 'client_invoices';

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'order_id',
        'sum',
    ];

    protected $casts = [
        'client_id' => 'int',
        'invoice_id' => 'int',
        'order_id' => 'int',
        'sum' => 'float',
    ];
}
