<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Models;

use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
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
        'quota_processing_method',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'quota_processing_method' => QuotaProcessingMethodEnum::class
    ];
}
