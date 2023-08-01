<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\Model;

class RoomBooking extends Model
{
    protected $table = 'booking_hotel_rooms';

    protected $fillable = [
        'booking_id',
        'hotel_room_id',
        'guests_count',
        'room_name',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('booking_hotel_rooms.*')
                ->join('bookings', 'bookings.id', '=', 'booking_hotel_rooms.booking_id')
                ->addSelect('bookings.order_id as booking_order_id');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_hotel_rooms.id', $id);
    }
}
