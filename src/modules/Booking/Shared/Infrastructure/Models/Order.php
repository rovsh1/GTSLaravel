<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Carbon\CarbonPeriodImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'note',
        'external_id',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => OrderStatusEnum::class,
        'source' => SourceEnum::class,
        'manual_client_penalty' => 'float',
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
            $builder->selectRaw(self::getClientPenaltyQuery() . ' as client_penalty');
            $builder->selectRaw(self::getClientPriceQuery() . ' as client_price');
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

        /** @var \stdClass|null $dates */
        $dates = DB::query()
            ->selectRaw('MIN(earliest_date) AS earliest_date')
            ->selectRaw('MAX(latest_date) AS latest_date')
            ->fromRaw($datesQuery)
            ->first();

        if (empty($dates) || (empty($dates->earliest_date) || empty($dates->latest_date))) {
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

    private static function getClientPriceQuery(): string
    {
        return "(SELECT SUM(client_price) FROM (SELECT order_id, COALESCE(client_manual_price, client_price) as client_price FROM bookings) as t WHERE t.order_id=orders.id)";
    }

    private static function getClientPenaltyQuery(): string
    {
//        return "COALESCE(order.client_penalty, (SELECT SUM(client_penalty) FROM bookings WHERE bookings.order_id = orders.id))";
        return "(SELECT SUM(client_penalty) FROM bookings WHERE bookings.order_id = orders.id)";
    }
}
