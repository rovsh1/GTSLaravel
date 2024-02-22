<?php

declare(strict_types=1);

namespace Module\Client\Payment\Infrastructure\Models;

use DateTime;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $payment_id
 * @property int $order_id
 * @property float $sum
 * @property-read DateTime $created_at
 */
class Landing extends Model
{
    const UPDATED_AT = null;

    protected $table = 'client_payment_landings';

    protected $fillable = [
        'payment_id',
        'order_id',
        'sum',
    ];

    protected $casts = [
        'client_id' => 'int',
        'order_id' => 'int',
        'sum' => 'float',
    ];
}
