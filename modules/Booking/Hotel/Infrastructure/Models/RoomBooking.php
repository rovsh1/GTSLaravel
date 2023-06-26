<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class RoomBooking extends Model
{
    protected $table = 'booking_hotel_rooms';

    protected $fillable = [
        'booking_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
