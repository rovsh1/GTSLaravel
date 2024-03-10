<?php

declare(strict_types=1);

namespace App\Admin\Models\Order;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class Order extends \Module\Booking\Shared\Infrastructure\Models\Order
{
    use HasQuicksearch;

    protected array $quicksearch = ['id'];

    public static function getActiveStatuses(): array
    {
        return [
            OrderStatusEnum::IN_PROGRESS,
            OrderStatusEnum::WAITING_INVOICE,
            OrderStatusEnum::INVOICED,
            OrderStatusEnum::PARTIAL_PAID,
        ];
    }

    public static function scopeWhereStatus(Builder $builder, OrderStatusEnum|int $status): void
    {
        $builder->where('orders.status', $status);
    }

    public function scopeWhereManagerId(Builder $builder, int $managerId): void
    {
        $builder->whereExists(function (Query $query) use ($managerId) {
            $query->selectRaw(1)
                ->from('administrator_orders as t')
                ->whereColumn('t.order_id', 'orders.id')
                ->where('t.administrator_id', $managerId);
        });
    }

    public function scopeWhereCreatedPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereBetween('orders.created_at', [
            $period->getStartDate()->startOfDay(),
            $period->getEndDate()->endOfDay()
        ]);
    }

    public function scopeWhereStartPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereExists(function (Query $query) use ($period) {
            $query->selectRaw(1)
                ->from('bookings')
                ->whereColumn('bookings.order_id', 'orders.id')
                ->leftJoin('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
                ->leftJoin('booking_other_details', 'booking_other_details.booking_id', 'bookings.id')
                ->leftJoin('booking_transfer_details', 'booking_transfer_details.booking_id', 'bookings.id')
                ->leftJoin('booking_airport_details', 'booking_airport_details.booking_id', 'bookings.id')
                ->where(function (Query $conditions) use ($period) {
                    $periodCondition = [
                        $period->getStartDate()->startOfDay(),
                        $period->getEndDate()->endOfDay()
                    ];

                    $conditions->whereBetween('booking_other_details.date', $periodCondition)
                        ->orWhereBetween('booking_airport_details.date', $periodCondition)
                        ->orWhereBetween('booking_transfer_details.date_start', $periodCondition)
                        ->orWhereBetween('booking_hotel_details.date_start', $periodCondition);
                });
        });
    }

    public function scopeWhereEndPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereExists(function (Query $query) use ($period) {
            $query->selectRaw(1)
                ->from('bookings')
                ->whereColumn('bookings.order_id', 'orders.id')
                ->leftJoin('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
                ->leftJoin('booking_other_details', 'booking_other_details.booking_id', 'bookings.id')
                ->leftJoin('booking_transfer_details', 'booking_transfer_details.booking_id', 'bookings.id')
                ->leftJoin('booking_airport_details', 'booking_airport_details.booking_id', 'bookings.id')
                ->where(function (Query $conditions) use ($period) {
                    $periodCondition = [
                        $period->getStartDate()->startOfDay(),
                        $period->getEndDate()->endOfDay()
                    ];

                    $conditions->whereBetween('booking_other_details.date', $periodCondition)
                        ->orWhereBetween('booking_airport_details.date', $periodCondition)
                        ->orWhereBetween('booking_transfer_details.date_end', $periodCondition)
                        ->orWhere(function (Query $transferConditions) use ($periodCondition) {
                            $transferConditions->whereNull('booking_transfer_details.date_end')
                                ->whereBetween('booking_transfer_details.date_start', $periodCondition);
                        })
                        ->orWhereBetween('booking_hotel_details.date_end', $periodCondition);
                });
        });
    }

    private static function getPayedAmountQuery(): string
    {
        return '(select COALESCE(SUM(sum), 0) from client_payment_landings where client_payment_landings.order_id = orders.id)';
    }
}
