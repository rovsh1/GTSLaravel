<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Infrastructure\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Support\Facades\DB;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;

/**
 * @method static Builder|Booking forPaymentId(int $paymentId)
 * @method static Builder|Booking forLandingToPaymentId(int $paymentId)
 * @method static Builder|Booking whereNotPaid()
 * @mixin Eloquent
 */
class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'supplier_currency' => CurrencyEnum::class,
        'supplier_penalty' => 'float',
        'supplier_manual_price' => 'float',
        'supplier_price' => 'float',
        'payed_amount' => 'float',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('bookings.*');
            $builder->selectRaw('COALESCE(supplier_manual_price, supplier_price) as supplier_price');
            $builder->selectRaw(self::getPayedAmountQuery() . ' as payed_amount');
            $builder->leftJoin('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id');
            $builder->leftJoin('booking_other_details', 'booking_other_details.booking_id', 'bookings.id');
            $builder->leftJoin('booking_transfer_details', 'booking_transfer_details.booking_id', 'bookings.id');
            $builder->leftJoin('booking_airport_details', 'booking_airport_details.booking_id', 'bookings.id');
            $builder->leftJoin('hotels', 'booking_hotel_details.hotel_id', 'hotels.id');
            $builder->leftJoin(
                'supplier_services',
                'supplier_services.id',
                DB::raw('COALESCE(booking_airport_details.service_id, booking_other_details.service_id, booking_transfer_details.service_id)'),
            );
            $builder->selectRaw('COALESCE(hotels.supplier_id, supplier_services.supplier_id) as supplier_id');
        });
    }

    public function scopeWhereNotPaid(Builder $builder): void
    {
        $supplierPriceQuery = 'COALESCE(supplier_penalty, supplier_manual_price, supplier_price)';
        $payedAmountQuery = self::getPayedAmountQuery();

        $builder->whereRaw("$payedAmountQuery < {$supplierPriceQuery}");
    }

    public function scopeForPaymentId(Builder $builder, int $paymentId): void
    {
        $builder->whereExists(function (Query $builder) use ($paymentId) {
            $builder->selectRaw(1)
                ->from('supplier_payments')
                ->where(function (Query $query) {
                    $query->whereColumn('supplier_payments.supplier_id', 'hotels.supplier_id')
                        ->orWhereColumn('supplier_payments.supplier_id', 'supplier_services.supplier_id');
                })
                ->whereColumn('supplier_payments.payment_currency', 'bookings.supplier_currency')
                ->where('supplier_payments.id', $paymentId);
        });
    }

    public function scopeForLandingToPaymentId(Builder $builder, int $paymentId): void
    {
        $builder->whereExists(function (Query $builder) use ($paymentId) {
            $builder->selectRaw(1)
                ->from('supplier_payment_landings')
                ->whereColumn('supplier_payment_landings.booking_id', 'bookings.id')
                ->where('supplier_payment_landings.payment_id', $paymentId);
        });
    }

    private static function getPayedAmountQuery(): string
    {
        return '(select COALESCE(SUM(sum), 0) from supplier_payment_landings where supplier_payment_landings.booking_id = bookings.id)';
    }
}
