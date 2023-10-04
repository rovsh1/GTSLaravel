<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\TransferBooking\Models;

use Sdk\Module\Database\Eloquent\Model;

class BookingDetails extends Model
{
    protected $table = 'booking_transfer_details';

    protected $fillable = [
        'booking_id',
        'car_id',
        'service_id',
        'city_id',
        'date',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
