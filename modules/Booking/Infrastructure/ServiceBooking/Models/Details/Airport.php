<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Sdk\Module\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'booking_airport_details';

    protected $fillable = [
        'booking_id',
        'airport_id',
        'service_id',
        'date',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
