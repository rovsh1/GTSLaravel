<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Order\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected $appends = [
        'guest_ids'
    ];

    public function guestIds(): Attribute
    {
        return Attribute::get(
            fn() => $this->guests()->pluck('id')->toArray()
        );
    }

    public function guests(): HasMany
    {
        return $this->hasMany(
            Guest::class,
            'order_id',
            'id'
        );
    }
}
