<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\Enum\SourceEnum;

/**
 * @method static Builder|Order forPaymentId(int $paymentId)
 * @method static Builder|Order forLandingToPaymentId(int $paymentId)
 * @method static Builder|Order whereNotPaid()
 * @mixin Eloquent
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'status',
        'invoice_id',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => OrderStatusEnum::class,
        'source' => SourceEnum::class,
        'client_price' => 'float',
        'payed_amount' => 'float',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('orders.*');
            $builder->selectRaw(self::getClientPriceQuery() . ' as client_price');
            $builder->selectRaw(self::getPayedAmountQuery() . ' as payed_amount');
        });
    }

    public function scopeWhereNotPaid(Builder $builder): void
    {
        $clientPriceQuery = self::getClientPriceQuery();
        $payedAmountQuery = self::getPayedAmountQuery();

        $builder->whereRaw("$payedAmountQuery < {$clientPriceQuery}");
    }

    public function scopeForPaymentId(Builder $builder, int $paymentId): void
    {
        $builder->whereExists(function (Query $builder) use ($paymentId) {
            $builder->selectRaw(1)
                ->from('client_payments')
                ->whereColumn('client_payments.client_id', 'orders.client_id')
                ->whereColumn('client_payments.payment_currency', 'orders.currency')
                ->where('client_payments.id', $paymentId);
        });
    }

    public function scopeForLandingToPaymentId(Builder $builder, int $paymentId): void
    {
        $builder->selectRaw(self::getPayedAmountQuery($paymentId) . ' as payed_amount')
            ->whereExists(function (Query $builder) use ($paymentId) {
                $builder->selectRaw(1)
                    ->from('client_payment_landings')
                    ->whereColumn('client_payment_landings.order_id', 'orders.id')
                    ->where('client_payment_landings.payment_id', $paymentId);
            });
    }

    private static function getClientPriceQuery(): string
    {
        $cancelledFeeStatus = StatusEnum::CANCELLED_FEE->value;
        $cancelledNoFeeStatus = StatusEnum::CANCELLED_NO_FEE->value;

        return "(SELECT SUM(client_price) FROM (SELECT order_id, IF(status IN ({$cancelledFeeStatus}, {$cancelledNoFeeStatus}), COALESCE(client_penalty, 0), COALESCE(client_manual_price, client_price)) as client_price FROM bookings) as t WHERE t.order_id=orders.id)";
    }

    private static function getPayedAmountQuery(?int $paymentId = null): string
    {
        $paymentQuery = $paymentId !== null ? "AND client_payment_landings.payment_id = {$paymentId}" : '';

        return "(select COALESCE(SUM(sum), 0) from client_payment_landings where client_payment_landings.order_id = orders.id {$paymentQuery})";
    }
}
