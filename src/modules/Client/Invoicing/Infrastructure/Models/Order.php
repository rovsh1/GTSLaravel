<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\Enum\SourceEnum;

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

            $builder->selectSub(function (Query $query) {
                $cancelledFeeStatus = BookingStatusEnum::CANCELLED_FEE->value;
                $cancelledNoFeeStatus = BookingStatusEnum::CANCELLED_NO_FEE->value;
                $query->selectRaw(
                    "(SELECT SUM(client_price) FROM (SELECT order_id, IF(status IN ({$cancelledFeeStatus}, {$cancelledNoFeeStatus}), COALESCE(client_penalty, 0), COALESCE(client_manual_price, client_price)) as client_price FROM bookings) as t WHERE t.order_id=orders.id)"
                );
            }, 'client_price');

            $builder->selectSub(function (Query $query) {
                $query->selectRaw('COALESCE(SUM(sum), 0)')
                    ->from('client_payment_plants')
                    ->whereColumn('client_payment_plants.order_id', 'orders.id');
            }, 'payed_amount');
        });
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

    public function scopeWhereLendToPaymentId(Builder $builder, int $paymentId): void
    {
        $builder->whereExists(function (Query $builder) use ($paymentId) {
            $builder->selectRaw(1)
                ->from('client_payment_plants')
                ->whereColumn('client_payment_plants.order_id', 'orders.id')
                ->where('client_payment_plants.payment_id', $paymentId);
        });
    }
}
