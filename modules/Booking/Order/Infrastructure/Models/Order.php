<?php

declare(strict_types=1);

namespace Module\Booking\Order\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'legal_id',
        'currency_id',
        'status',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];
}
