<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Sdk\Module\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 'booking_transfer_details';

    protected $fillable = [
        'booking_id',
        'car_id',
        'service_id',
        'city_id',
        'date_start',
        'date_end',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
