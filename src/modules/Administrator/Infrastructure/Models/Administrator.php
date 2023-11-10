<?php

namespace Module\Administrator\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $table = 'administrators';

    protected $fillable = [];

    public function scopeWhereBookingId(Builder $builder, int $bookingId): void
    {
        $builder->whereExists(function (Query $query) use ($bookingId) {
            $query->selectRaw(1)
                ->from('administrator_bookings')
                ->whereColumn('administrator_bookings.administrator_id', 'administrators.id')
                ->where('booking_id', $bookingId);
        });
    }

    public function scopeWhereOrderId(Builder $builder, int $orderId): void
    {
        $builder->whereExists(function (Query $query) use ($orderId) {
            $query->selectRaw(1)
                ->from('administrator_orders')
                ->whereColumn('administrator_orders.administrator_id', 'administrators.id')
                ->where('order_id', $orderId);
        });
    }
}
