<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as Query;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\Order\OrderStatusEnum;
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
        'client_price' => 'float'
    ];

    protected $appends = [
        'guest_ids'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('orders.*');

            $builder->selectSub(function (Query $query) {
                $cancelledFeeStatus = BookingStatusEnum::CANCELLED_FEE->value;
                $cancelledNoFeeStatus = BookingStatusEnum::CANCELLED_NO_FEE->value;
                $query->selectRaw(
                    "(SELECT SUM(client_price) FROM (SELECT order_id, IF(status IN ({$cancelledFeeStatus}, {$cancelledNoFeeStatus}), COALESCE(client_penalty, 0), COALESCE(client_manual_price, client_price)) as client_price FROM bookings) as t WHERE t.order_id=orders.id)"
                );
            }, 'client_price');
        });
    }

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
