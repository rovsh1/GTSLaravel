<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class BookingDetails extends Model
{
    protected $table = 'booking_airport_details';

    protected $fillable = [
        'booking_id',
        'airport_id',
        //@todo скорее всего дата, для фильтрации
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
