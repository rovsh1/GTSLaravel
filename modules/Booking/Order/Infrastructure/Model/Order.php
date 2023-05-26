<?php

declare(strict_types=1);

namespace Module\Booking\Order\Infrastructure\Model;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Booking\Hotel\Infrastructure\Models\Client;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'status'
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeWithoutHotelBookings(Builder $builder): void
    {
        $builder->whereNotExists(function (\Illuminate\Database\Query\Builder $query) {
            $query->selectRaw('1')
                ->from('bookings')
                ->whereRaw('`orders`.`id` = `bookings`.`order_id`')
                //@todo hack енум из другого модуля
                ->where('bookings.type', BookingTypeEnum::HOTEL);
        });
    }
}
