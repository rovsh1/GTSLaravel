<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Models\Hotel;

use Custom\Framework\Database\Eloquent\Model;

class BookingDetails extends Model
{
    protected $table = 'booking_hotel_details';

    protected $fillable = [
        'booking_id',
        'hotel_id',
        'date_start',
        'date_end',
        'additional_data',//@todo Тип номера подтверждения бронирования
        'rooms',
    ];
}
