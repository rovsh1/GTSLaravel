<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Order\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Shared\Enum\Booking\OrderStatusEnum;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\SourceEnum;
use Sdk\Module\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'legal_id',
        'currency',
        'status',
        'source',
        'creator_id',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => OrderStatusEnum::class,
        'source' => SourceEnum::class,
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
