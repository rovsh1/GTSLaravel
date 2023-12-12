<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Carbon\CarbonPeriodImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Support\Facades\DB;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\Enum\SourceEnum;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'legal_id',
        'currency',
        'status',
        'voucher',
        'source',
        'creator_id',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => OrderStatusEnum::class,
        'source' => SourceEnum::class,
        'client_price' => 'float',
        'payed_amount' => 'float',
        'voucher' => 'array',
    ];

    protected $appends = [
        'guest_ids'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('orders.*');

            $builder->selectSub(function (Query $query) {
                $cancelledFeeStatus = StatusEnum::CANCELLED_FEE->value;
                $cancelledNoFeeStatus = StatusEnum::CANCELLED_NO_FEE->value;
                $query->selectRaw(
                    "(SELECT SUM(client_price) FROM (SELECT order_id, IF(status IN ({$cancelledFeeStatus}, {$cancelledNoFeeStatus}), COALESCE(client_penalty, 0), COALESCE(client_manual_price, client_price)) as client_price FROM bookings) as t WHERE t.order_id=orders.id)"
                );
            }, 'client_price');
        });
    }

    public function getPeriod(): ?CarbonPeriodImmutable
    {
        $orderIdCondition = "booking_id IN (SELECT id FROM bookings WHERE order_id = {$this->id})";
        $hotelDatesQuery = "SELECT MIN(date_start) AS earliest_date, MAX(date_end) AS latest_date FROM booking_hotel_details WHERE {$orderIdCondition}";
        $transferDatesQuery = "SELECT MIN(date_start) AS earliest_date, MAX(date_end) AS latest_date FROM booking_transfer_details WHERE {$orderIdCondition}";
        $airportDatesQuery = "SELECT MIN(date) AS earliest_date, MAX(date) AS latest_date FROM booking_airport_details WHERE {$orderIdCondition}";
        $otherDatesQuery = "SELECT MIN(date) AS earliest_date, MAX(date) AS latest_date FROM booking_other_details WHERE {$orderIdCondition}";
        $datesQuery = "({$hotelDatesQuery} UNION {$transferDatesQuery} UNION {$airportDatesQuery} UNION {$otherDatesQuery}) as dates";

        $dates = DB::query()
            ->selectRaw('MIN(earliest_date) AS earliest_date')
            ->selectRaw('MAX(latest_date) AS latest_date')
            ->fromRaw($datesQuery)
            ->first();

        if (empty($dates)) {
            return null;
        }

        return new CarbonPeriodImmutable(
            $dates->earliest_date,
            $dates->latest_date,
        );
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
