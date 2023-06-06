<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class BookingDetails extends Model
{
    protected $table = 'booking_hotel_details';

    protected $fillable = [
        'booking_id',
        'hotel_id',
        'date_start',
        'date_end',
        'nights_count',
        'data',
    ];
}
