<?php

declare(strict_types=1);

namespace Module\Client\Payment\Infrastructure\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\Enum\SourceEnum;

/**
 * @method static Builder|Order withAmounts()
 *
 * @mixin Eloquent
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'status',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => OrderStatusEnum::class,
        'source' => SourceEnum::class,
        'client_price' => 'float',
        'payed_amount' => 'float',
    ];

    public function scopeWithAmounts(Builder $builder): void
    {
        $builder->addSelect('orders.*');
        $builder->selectRaw(self::getClientPriceQuery() . ' as client_price');
        $builder->selectRaw(self::getPayedAmountQuery() . ' as payed_amount');
    }

    private static function getClientPriceQuery(): string
    {
        $cancelledFeeStatus = StatusEnum::CANCELLED_FEE->value;
        $cancelledNoFeeStatus = StatusEnum::CANCELLED_NO_FEE->value;

        return "(SELECT SUM(client_price) FROM (SELECT order_id, IF(status IN ({$cancelledFeeStatus}, {$cancelledNoFeeStatus}), COALESCE(client_penalty, 0), COALESCE(client_manual_price, client_price)) as client_price FROM bookings) as t WHERE t.order_id=orders.id)";
    }

    private static function getPayedAmountQuery(): string
    {
        return '(select COALESCE(SUM(sum), 0) from client_payment_landings where client_payment_landings.order_id = orders.id)';
    }
}
