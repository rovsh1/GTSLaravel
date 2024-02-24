<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Infrastructure\Models;

use DateTime;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $payment_id
 * @property int $booking_id
 * @property float $sum
 * @property-read DateTime $created_at
 */
class Landing extends Model
{
    const UPDATED_AT = null;

    protected $table = 'supplier_payment_landings';

    protected $fillable = [
        'payment_id',
        'booking_id',
        'sum',
    ];

    protected $casts = [
        'client_id' => 'int',
        'booking_id' => 'int',
        'sum' => 'float',
    ];
}
